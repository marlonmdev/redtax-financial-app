<?php

namespace App\Http\Controllers;

use ZipArchive;
use App\Models\Client;
use App\Models\Upload;
use App\Models\AuditLog;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{

    public function index()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'id');
        $sortDirection = request('sort_direction', 'desc');

        // Perform the search with Laravel Scout
        $query = Document::search($search)->query(function ($builder) {
            // Check if the authenticated user's role is 'Client'
            if (auth()->user()->role->role_name === 'Client') {
                // Get the current authenticated user client_id
                $clientId = Client::where('user_id', auth()->user()->id)->value('id');
                // Only show documents uploaded by the authenticated user
                $builder->where('client_id', $clientId);
            }
        });

        // Get all results matching the search query
        $allResults = $query->get();

        // Apply sorting based on field type
        $sortedResults = $allResults->sortBy(function ($user) use ($sortField, $sortDirection) {
            $value = $user->{$sortField};

            // Determine if the value is numeric
            if (is_numeric($value)) {
                $value = (int) $value; // Ensure value is treated as integer
            }

            return $value;
        }, SORT_REGULAR, $sortDirection === 'desc');


        // Paginate the sorted results manually
        $currentPage = $page;
        $results = $sortedResults->forPage($currentPage, $perPage);

        // Create a LengthAwarePaginator instance
        $documents = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $sortedResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );

        // Load relationships
        $documents->load('client');

        return view('documents.index', [
            'documents' => $documents,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function uploadFiles(Request $request)
    {
        $request->validate([
            'document' => ['required', 'array', 'max:12'],
            'document.*' => [
                function ($attribute, $value, $fail) {
                    $allowedExtensions = ['jpg', 'jpeg', 'pdf', 'docx', 'doc', 'xlsx', 'xls', 'csv'];
                    $extension = $value->getClientOriginalExtension();

                    if (!in_array($extension, $allowedExtensions)) {
                        $fail('Files must be of type: jpg, pdf, docx, doc ,xlsx, xls, and csv.');
                    }
                },
                'max:3072'
            ],
        ]);

        $files = $request->file('document');

        $client = Client::where('user_id', auth()->user()->id)->first();

        if (count($files) > 1) {
            // if more than one file, zip them
            $zip = new ZipArchive();
            $zipFileName = 'zip_' . time() . '.zip';
            $zipFilePath = storage_path('app/public/' .  $zipFileName);

            if ($zip->open($zipFilePath, ZipArchive::CREATE) !== TRUE) {
                notify()->error('Unable to create ZIP file', 'Error');
                return redirect()->route('documents.index');
            }

            foreach ($files as $file) {
                $zip->addFile($file->getRealPath(), $file->getClientOriginalName());
            }

            $zip->close();

            if (!file_exists($zipFilePath)) {
                notify()->error('ZIP file not found after creation', 'Error');
                return redirect()->route('documents.index');
            }

            // Create a document record for the zip file
            $documentId = $this->storeClientDocument($client->id, $client->name, $zipFilePath, true);

            if ($zipFilePath && file_exists($zipFilePath)) {
                // Delete the ZIP file after processing
                unlink($zipFilePath);
            }
        } else {
            // Handle single file upload
            $documentId = $this->storeClientDocument($client->id, $client->name, $files[0]);
        }

        if (!$this->storeClientUpload($client->id, $client->name, $client->email, $client->phone,  $documentId)) {
            notify()->error('Upload Record Save Failed', 'Error');
            return redirect()->route('documents.index');
        }

        notify()->success('Document/s uploaded successfully', 'Success');
        return redirect()->route('documents.index');
    }

    public function storeClientDocument($clientId, $clientName, $fileOrPath, $isZip = false)
    {
        if ($isZip) {
            // Handle ZIP file
            $zipPath = $fileOrPath;
            $fileName = basename($zipPath);
            $fileSize = $this->formatFileSize(filesize($zipPath));

            // Ensure the ZIP file is stored in the correct directory
            $uploadPath = 'documents/client_' . $clientId;
            $newFilePath = $uploadPath . '/' . $fileName;

            // Store the ZIP file in the public disk
            Storage::disk('public')->put($newFilePath, file_get_contents($zipPath));

            $filePath = $newFilePath;
        } else {
            // Handle single file
            $fileName = $fileOrPath->getClientOriginalName();
            $filePath = $fileOrPath->store('documents/client_' . $clientId, 'public');
            $fileSize = $this->formatFileSize(filesize($fileOrPath));
        }

        // Extract file extension and name without extension
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileNameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);

        // Sanitize the file name
        $fileNameWithoutExtension = Str::slug($fileNameWithoutExtension, '-');
        $uploadPath = 'documents/client_' . $clientId;

        // Generate a unique file name if it already exists
        $fileName = $fileNameWithoutExtension . '.' . $fileExtension;
        $counter = 1;
        while (Storage::disk('public')->exists($uploadPath . '/' . $fileName)) {
            $fileName = $fileNameWithoutExtension . '-' . $counter . '.' . $fileExtension;
            $counter++;
        }

        // Create or update the document record
        $document = Document::create([
            'client_id' => $clientId,
            'document_name' => $fileName,
            'document_size' => $fileSize,
            'document_extension' => $fileExtension,
            'uploaded_by' => $clientName,
            'upload_date' => now(),
            'access_level' => null,
            'uploaded_by_agent_id' => null,
            'document_path' => $filePath
        ]);

        return $document->id;
    }

    private function formatFileSize($sizeInBytes)
    {
        return $sizeInBytes < 1048576
            ? number_format($sizeInBytes / 1024, 2) . ' KB' // Convert size to KB if less than 1 MB
            : number_format($sizeInBytes / 1048576, 2) . ' MB'; // Convert size to MB if 1 MB or more
    }

    public function storeClientUpload($clientId, $clientName, $clientEmail, $clientPhone, $documentId)
    {
        $upload = new Upload;
        $upload->name = $clientName;
        $upload->email = $clientEmail;
        $upload->phone = $clientPhone;
        $upload->client_id = $clientId;
        $upload->document_id = $documentId;
        return $upload->save();
    }


    public function updateViewedStatus(Document $document)
    {
        // Update the document viewed status
        if ($document->viewed !== 1) {
            $document->update([
                'viewed' => 1
            ]);
        }

        // Save the view log
        $auditLog = new AuditLog;
        $auditLog->user_id = auth()->user()->id;
        $auditLog->name = auth()->user()->name;
        $auditLog->activity = "Viewed a document named " . $document->document_name;
        $auditLog->save();
    }

    public function updateDownloadedStatus(Document $document)
    {
        // Update the document downloaded status
        if ($document->downloaded !== 1) {
            $document->update([
                'downloaded' => 1
            ]);
        }

        // Save the download log
        $auditLog = new AuditLog;
        $auditLog->user_id = auth()->user()->id;
        $auditLog->name = auth()->user()->name;
        $auditLog->activity = "Downloaded a document named " . $document->document_name;
        $auditLog->save();
    }

    public function download(Document $document)
    {
        $filePath = 'storage/' . $document->document_path;
        $extension = pathinfo($document->document_name, PATHINFO_EXTENSION);
        $fileName = 'download-' . time() . '.' . $extension;

        // Update the document downloaded status
        if ($document->downloaded !== 1) {
            $document->update([
                'downloaded' => 1
            ]);
        }

        // Save to audit logs
        $auditLog = new AuditLog;
        $auditLog->user_id = auth()->user()->id;
        $auditLog->name = auth()->user()->name;
        $auditLog->activity = "Downloaded a document named " . $document->document_name;
        $auditLog->save();

        return response()->download($filePath, $fileName);
    }

    public function destroy(Document $document)
    {
        $docName = $document->document_name;

        if (Storage::disk('public')->exists($document->document_path)) {
            Storage::disk('public')->delete($document->document_path);
        }

        $document->delete();

        // Save the delete log
        $auditLog = new AuditLog;
        $auditLog->user_id = auth()->user()->id;
        $auditLog->name = auth()->user()->name;
        $auditLog->activity = "Deleted a document named " . $docName;
        $auditLog->save();

        notify()->success('Document Deleted Successfully', 'Success');
        return redirect()->route('documents.index');
    }
}
