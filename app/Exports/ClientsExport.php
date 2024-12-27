<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ClientsExport implements FromCollection, WithHeadings, WithCustomCsvSettings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Client::select(
            DB::raw('MAX(added_on) as latest_added_on'),
            'name',
            'email',
            'phone',
            'preferred_contact',
            'address',
            'city',
            'state',
            'zip_code',
            'tax_identification_number',
            'customer_type',
            'referred_by',
        )
            ->groupBy('name', 'email', 'phone', 'preferred_contact', 'address', 'city', 'state', 'zip_code', 'tax_identification_number', 'customer_type', 'referred_by')
            ->orderBy('latest_added_on', 'asc')
            ->get()
            ->map(function ($row) {
                return [
                    'added_on' =>  $this->formatDate($row->latest_added_on),
                    'name'    => $row->name,
                    'email'    => $row->email,
                    'phone'    => $row->phone,
                    'preferred_contact'    => $row->preferred_contact,
                    'address'   =>  $row->address,
                    'city'   =>  $row->city,
                    'state'   =>  $row->state,
                    'zip_code'   =>  $row->zip_code,
                    'tax_identification_number'   =>  $row->tax_identification_number,
                    'customer_type'   =>  $row->customer_type,
                    'referred_by'   =>  $row->referred_by,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Date Added',
            'Full Name',
            'Email',
            'Phone Number',
            'Prefer Contact via',
            'Address',
            'City',
            'State',
            'Zip Code',
            'Tax Identification Number',
            'Customer Type',
            'Referred By',
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'use_bom'   => true,
        ];
    }

    private function formatDate($date)
    {
        if ($date) {
            return Carbon::parse($date)->format('m/d/Y');
        }
        return null;
    }
}
