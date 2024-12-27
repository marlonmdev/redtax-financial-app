<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Appointment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AppointmentsExport implements FromCollection, WithHeadings
{
    protected $status;

    // Constructor to accept status (pending, completed, or all)
    public function __construct($status = 'All')
    {
        $this->status = $status;
    }

    public function collection()
    {
        $query = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->select([
                'appointments.status',
                'services.service as service',
                'appointments.date',
                'appointments.start_time',
                'appointments.end_time',
                'appointments.name',
                'appointments.email',
                'appointments.phone',
                'appointments.location',
                'appointments.details',
                'appointments.referred_by',
            ]);

        // Apply filtering based on status if it's not 'All'
        if ($this->status !== 'All') {
            $query->where('appointments.status', $this->status);
        }

        return $query->orderBy('appointments.date', 'asc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'status' => $appointment->status,
                    'service' => $appointment->service,
                    'formatted_date' => $this->formatDate($appointment->date),
                    'time_range' => $this->formatTimeRange($appointment->start_time, $appointment->end_time),
                    'name' => $appointment->name,
                    'email' => $appointment->email,
                    'phone' => $appointment->phone,
                    'location' => $appointment->location,
                    'details' => $appointment->details,
                    'referred_by' => $appointment->referred_by,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Status',
            'Service Name',
            'Appointment Date',
            'Appointment Time',
            'Full Name',
            'Email',
            'Phone Number',
            'Location',
            'Details',
            'Referred By',
        ];
    }

    private function formatDate($date)
    {
        return $date ? Carbon::parse($date)->format('m/d/Y') : null;
    }

    private function formatTimeRange($start_time, $end_time)
    {
        if ($start_time && $end_time) {
            return Carbon::parse($start_time)->format('g:i a') . ' - ' . Carbon::parse($end_time)->format('g:i a');
        }
        return null;
    }
}
