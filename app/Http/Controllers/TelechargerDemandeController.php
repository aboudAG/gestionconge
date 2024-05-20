<?php
namespace App\Http\Controllers;

use App\Models\Demande; // Import the PDF facade
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TelechargerDemandeController extends Controller
{
    public function download($id)
    {
        $demande = Demande::with('type', 'statuts.etape', 'statuts.approbateur')->findOrFail($id);

        $pdf = Pdf::loadView('pdf.leave_request', compact('demande'));
        return $pdf->download('demande_conge_' . $demande->ID . '.pdf');
    }
}