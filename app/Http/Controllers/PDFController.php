<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function saveToPDF()
    {
        // Load the view and capture only the content inside #engagement-letter
        $pdf = Pdf::loadView('dashboard.partials.engagement-letter-content')->setOptions(['isRemoteEnabled' => true]);

        return $pdf->download('engagement-letter.pdf');
    }
}
