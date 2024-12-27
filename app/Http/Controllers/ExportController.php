<?php

namespace App\Http\Controllers;

use App\Exports\AppointmentsExport;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Exports\ClientsExport;
use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportClientProfilesCSV()
    {
        $fileName = 'redtax-client-profiles-' . time() . '.csv';

        // Save the export log
        $auditLog = new AuditLog;
        $auditLog->user_id = auth()->user()->id;
        $auditLog->name = auth()->user()->name;
        $auditLog->activity = "Exported client profiles as a CSV file";
        $auditLog->save();

        return Excel::download(new ClientsExport,  $fileName);
    }

    public function exportClientProfilesPDF()
    {
        $fields = [
            'name',
            'email',
            'phone',
            'address',
            'city',
            'state',
            'zip_code',
            'customer_type',
            'referred_by'
        ];

        $clients = Client::select(DB::raw('MAX(added_on) as latest_added_on'), ...$fields)
            ->groupBy($fields)
            ->orderBy('latest_added_on', 'asc')
            ->get()
            ->map(function ($row) use ($fields) {
                $formatted = ['latest_added_on' => $row->latest_added_on];
                foreach ($fields as $field) {
                    $formatted[$field] = $row->$field;
                }
                return $formatted;
            });

        $currentDateTime = Carbon::now()->format('F d, Y g:i a');

        $pdf = Pdf::loadView('clients.client-profiles-pdf', compact('clients', 'currentDateTime'))
            ->setPaper('a4', 'landscape')
            ->setOptions(['isRemoteEnabled' => true]);

        $fileName = 'redtax-client-profiles-' . time() . '.pdf';

        // Save the export log
        $auditLog = new AuditLog;
        $auditLog->user_id = auth()->user()->id;
        $auditLog->name = auth()->user()->name;
        $auditLog->activity = "Exported client profiles as a PDF file";
        $auditLog->save();

        return $pdf->download($fileName);
    }

    public function exportAppointmentsCSV($status = 'All')
    {
        $fileName = 'redtax-appointments-' . time() . '.csv';

        if ($status == 'Pending') {
            $fileName = 'redtax-pending-appointments-' . time() . '.csv';
        } elseif ($status == 'Completed') {
            $fileName = 'redtax-completed-appointments-' . time() . '.csv';
        }

        // Save the export log
        $auditLog = new AuditLog;
        $auditLog->user_id = auth()->user()->id;
        $auditLog->name = auth()->user()->name;
        $auditLog->activity = "Exported appointments as a CSV file";
        $auditLog->save();

        return Excel::download(new AppointmentsExport($status),  $fileName);
    }

    public function exportAppointmentsPDF($status = 'All')
    {
        $fields = [
            'appointments.date',
            'appointments.start_time',
            'appointments.end_time',
            'appointments.name',
            'appointments.email',
            'appointments.phone',
            'appointments.location',
            'appointments.status',
            'appointments.referred_by',
            'services.service'
        ];

        $query = Appointment::select($fields)
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->groupBy('appointments.date', 'appointments.start_time', 'appointments.end_time', 'appointments.name', 'appointments.email', 'appointments.phone', 'appointments.location', 'appointments.status', 'appointments.referred_by', 'services.service')
            ->orderBy('date', 'asc');

        // Apply status filtering if the status is not 'All'
        if ($status !== 'All') {
            $query->where('appointments.status', $status);
        }

        // Execute the query and map the results
        $appointments = $query->get()->map(function ($row) use ($fields) {
            $formatted = ['latest_added_on' => $row->latest_added_on];
            foreach ($fields as $field) {
                $fieldKey = strpos($field, '.') !== false ? explode('.', $field)[1] : $field;
                $formatted[$fieldKey] = $row->$fieldKey;
            }
            return $formatted;
        });

        $currentDateTime = Carbon::now()->format('F d, Y g:i a');

        // Load PDF view with appointments data and current date time
        $pdf = Pdf::loadView('appointments.appointments-pdf', compact('appointments', 'currentDateTime'))
            ->setPaper('a4', 'landscape')
            ->setOptions(['isRemoteEnabled' => true]);

        // Set file name based on the status
        $fileName = 'redtax-appointments-' . time() . '.pdf';
        if ($status == 'Pending') {
            $fileName = 'redtax-pending-appointments-' . time() . '.pdf';
        } elseif ($status == 'Completed') {
            $fileName = 'redtax-completed-appointments-' . time() . '.pdf';
        }

        // Save the export log
        $auditLog = new AuditLog;
        $auditLog->user_id = auth()->user()->id;
        $auditLog->name = auth()->user()->name;
        $auditLog->activity = "Exported appointments as a PDF file";
        $auditLog->save();

        return $pdf->download($fileName);
    }
}
