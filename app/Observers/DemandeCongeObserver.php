<?php

namespace App\Observers;

use App\Models\Demande;
use Carbon\Carbon;
    class DemandeCongeObserver
    {
        public function creating(Demande $demandeConge)
        {
            // Déterminer le préfixe en fonction du type de demande
            $type = $demandeConge->type->NOM;
            switch ($type) {
                case 'Annuel':
                    $prefix = 'ANN';
                    break;
                case 'Maternite':
                    $prefix = 'MAT';
                    break;
                case 'Maladie':
                    $prefix = 'MAL';    
                    break;
                case 'Sans Solde':
                    $prefix = 'SSD';
                    break;
                default:
                    $prefix = 'UNK';
                    break;
            }
    
            // Obtenir l'année et le mois actuels
            $year = Carbon::now()->format('y'); // Deux derniers chiffres de l'année
            $month = Carbon::now()->format('m'); // Mois à deux chiffres
    
            // Générer l'ID avec préfixe, année, mois et séquentiel
            $lastDemande = Demande::where('ID', 'like', "$prefix$year$month-%")->orderBy('ID', 'desc')->first();
            
    
            if ($lastDemande) {
                $lastIdNumber = intval(substr($lastDemande->ID, -4));
                $newIdNumber = $lastIdNumber + 1;
            } else {
                $newIdNumber = 1;
            }
    
            $demandeConge->ID = sprintf('%s%s%s-%04d', $prefix, $year, $month, $newIdNumber);
        }
    }