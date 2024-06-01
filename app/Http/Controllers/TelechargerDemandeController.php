<?php
namespace App\Http\Controllers;

use App\Models\Demande;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TelechargerDemandeController extends Controller
{
    public function download($id)
    {
        $demande = Demande::with('type', 'statuts.etape', 'statuts.approbateur')->findOrFail($id);
        $qrCodeData = QrCode::format('svg')->size(150)->generate(route('demandes.verify', $demande->ID));

        // Convertir en base64
        $qrCodeBase64 = base64_encode($qrCodeData); 

        $pdf = Pdf::loadView('pdf.leave_request', compact('demande', 'qrCodeBase64'));
        return $pdf->download('demande_conge_' . $demande->ID . '.pdf');
    }
}