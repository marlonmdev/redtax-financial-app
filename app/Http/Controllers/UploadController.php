<?php

namespace App\Http\Controllers;

use ZipArchive;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\Upload;
use App\Models\Document;
use App\Enums\StatusType;
use App\Enums\PriorityType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ClientService;
use App\Models\ClientQuotation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UploadDocumentRequest;

class UploadController extends Controller
{
    public function index()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'id');
        $sortDirection = request('sort_direction', 'desc');

        // Perform the search with Laravel Scout and load note counts
        $query = Upload::search($search);

        // Get all results matching the search query
        $allResults = $query->get();

        // Apply sorting based on field type
        $sortedResults = $allResults->sortBy(function ($task) use ($sortField, $sortDirection) {
            $value = $task->{$sortField};

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
        $uploads = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $sortedResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );


        $statusTypes = StatusType::cases();

        return view('uploads.index', [
            'uploads' => $uploads,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
            'statusTypes' => $statusTypes
        ]);
    }

    public function upload(UploadDocumentRequest $request)
    {
        $validated = $request->validated();
        $clientName = ucwords($validated['name']);

        $existingClient = DB::table('clients')
            ->whereRaw('LOWER(name) = ?', [strtolower($validated['name'])])
            ->first();

        $existingUserAccount = DB::table('users')
            ->where('email', $validated['email'])
            ->first();

        $insertedUserId = null;
        $clientId = null;

        // Check if client exists
        if ($existingClient) {
            $clientId = $existingClient->id;

            // Determine the user ID to associate with the client
            if (is_null($existingClient->user_id)) {
                // Handle user account creation if it doesn't exist 
                $insertedUserId = $existingUserAccount ? $existingUserAccount->id : $this->createClientUserAccount($clientName, $validated['email']);

                // Update the existing client's user_id if a new user was created or an existing user was found
                if ($insertedUserId) {
                    DB::table('clients')->where('id', $clientId)->update(['user_id' => $insertedUserId]);
                }
            }
        } else {
            // Handle user account creation if it doesn't exist            
            $insertedUserId = $existingUserAccount ? $existingUserAccount->id : $this->createClientUserAccount($clientName, $validated['email']);

            // Insert a new client
            $clientId = DB::table('clients')->insertGetId([
                'name' => ucwords($validated['name']),
                'customer_type' => $validated['customer_type'],
                'company' => $validated['company'] ?? null,
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'preferred_contact' => $validated['preferred_contact'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'zip_code' => $validated['zip_code'],
                'tax_identification_number' => null,
                'referred_by' => $validated['referred_by'] ?? null,
                'assigned_agent_id' => null,
                'user_id' => $insertedUserId,
            ]);
        }

        if (!$clientId) {
            notify()->error('Client Creation Failed', 'Error');
            return redirect()->route('document-upload');
        }

        if (isset($validated['services'])) {
            $this->storeClientService($clientId, $validated);
        }

        $files = $request->file('document');

        if (count($files) > 1) {
            // if more than one file, zip them
            $zip = new ZipArchive();
            $zipFileName = 'zip_' . time() . '.zip';
            $zipFilePath = storage_path('app/public/' .  $zipFileName);

            if ($zip->open($zipFilePath, ZipArchive::CREATE) !== TRUE) {
                notify()->error('Unable to create ZIP file', 'Error');
                return redirect()->route('document-upload');
            }

            foreach ($files as $file) {
                $zip->addFile($file->getRealPath(), $file->getClientOriginalName());
            }

            $zip->close();

            if (!file_exists($zipFilePath)) {
                notify()->error('ZIP file not found after creation', 'Error');
                return redirect()->route('document-upload');
            }

            // Create a document record for the zip file
            $documentId = $this->storeClientDocument($clientId, $clientName, $zipFilePath, true);

            if ($zipFilePath && file_exists($zipFilePath)) {
                // Delete the ZIP file after processing
                unlink($zipFilePath);
            }
        } else {
            // Handle single file upload
            $documentId = $this->storeClientDocument($clientId, $clientName, $files[0]);
        }

        if (!$this->storeClientUpload($clientId, $clientName, $validated['email'], $validated['phone'],  $documentId)) {
            notify()->error('Upload Creation Failed', 'Error');
            return redirect()->route('document-upload');
        }

        notify()->success('Document Uploaded Successfully', 'Success');
        return redirect()->route('document-upload');
    }

    public function createClientUserAccount($clientName, $clientEmail)
    {
        $user = new User;
        $user->name = $clientName;
        $user->email = $clientEmail;
        $user->password = Hash::make(Str::random(8));
        $user->role_id = Role::where('role_name', 'Client')->value('id');
        $user->has_access = 0;

        if ($user->save()) {
            return $user->id;
        }

        return false;
    }

    public function storeClientService($clientId, $validated)
    {
        $clientService = new ClientService;
        $clientService->client_id = $clientId;
        $clientService->services = $validated['services'];
        $clientService->details = $validated['details'];
        $clientService->save();
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


    // public function storeClientDocumentOld($clientId, $clientName, $file)
    // {
    //     $originalFileName = $file->getClientOriginalName();
    //     $fileExtension = $file->getClientOriginalExtension();
    //     $fileNameWithoutExtension = pathinfo($originalFileName, PATHINFO_FILENAME);

    //     // Sanitize the file name
    //     $fileNameWithoutExtension = Str::slug($fileNameWithoutExtension, '-');
    //     $uploadPath = 'documents/' . 'client_' . $clientId;

    //     // Generate a unique file name
    //     $fileName = $fileNameWithoutExtension . '.' . $fileExtension;
    //     $counter = 1;
    //     while (Storage::disk('public')->exists($uploadPath . '/' . $fileName)) {
    //         $fileName = $fileNameWithoutExtension . '-' . $counter . '.' . $fileExtension;
    //         $counter++;
    //     }

    //     $sizeInBytes = $file->getSize();

    //     // Determine the size format to use
    //     if ($sizeInBytes < 1048576) {
    //         // Convert the size to kilobytes (KB)
    //         $fileSize = number_format($sizeInBytes / 1024, 2) . ' KB';
    //     } else {
    //         // Convert the size to megabytes (MB)
    //         $fileSize = number_format($sizeInBytes / 1048576, 2) . ' MB';
    //     }

    //     $document = Document::create([
    //         'client_id' => $clientId,
    //         'document_name' => $fileName,
    //         'document_size' => $fileSize,
    //         'document_extension' => $fileExtension,
    //         'uploaded_by' => $clientName,
    //         'upload_date' => now(),
    //         'access_level' => null,
    //         'uploaded_by_agent_id' => null,
    //         'document_path' => $file->storeAs($uploadPath, $fileName, 'public')
    //     ]);

    //     return $document->id;
    // }

    public function storeClientTask($clientId,  $clientName, $documentId)
    {
        $taskPerformed =  "Document Upload";
        $task = new Task;
        $task->title = $taskPerformed;
        $task->description = $taskPerformed;
        $task->status = StatusType::NOT_STARTED->value;
        $task->priority = PriorityType::LOW->value;
        $task->due_date = now()->format('Y-m-d');
        $task->assigned_by = null;
        $task->assigned_to = $clientName;
        $task->client_id = $clientId;
        $task->document_id = $documentId;
        $task->assigned_agent_id = null;
        return $task->save();
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

    public function updateStatus(Upload $upload, Request $request)
    {
        $validated = $request->validate([
            'status' => ['required']
        ]);

        $updated = $upload->update([
            'status' => $validated['status']
        ]);

        if (!$updated) {
            notify()->error('Upload Status Update Failed', 'Error');
            return redirect()->route('uploads.index');
        }

        notify()->success('Upload Status Updated Successfully', 'Success');
        return redirect()->route('uploads.index');
    }


    public function destroy(Upload $upload)
    {
        if (!$upload->delete()) {
            notify()->error('Upload Deleted Failed', 'Error');
            return redirect()->route('uploads.index');
        }

        notify()->success('Upload Deleted Successfully', 'Success');
        return redirect()->route('uploads.index');
    }
}
