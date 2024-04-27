<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employe;
use App\Models\DroitConge;

class CreateAnnualLeaveRights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leaves:create-annual-rights';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crée un nouveau droit de congé de 30 jours pour chaque employé pour la nouvelle année';

    /**
     * Execute the console command.
     */
    public function handle()
{
    $this->info('Démarrage de la création des droits de congé annuels...');

    // Récupérer tous les employés actifs
    $employes = Employe::all(); // Assurez-vous que Employe est le bon modèle pour vos employés
    $currentYear = now()->year;

    foreach ($employes as $employe) {
        // Créer un nouveau droit de congé pour l'année actuelle
        DroitConge::create([
            'EMPLOYE_ID' => $employe->MATRICULE,
            'ANNEE' => $currentYear,
            'JOURS_PRIS' => 0,
            'JOURS_RESTANT' => 30,
        ]);
    }

    $this->info('Les droits de congé ont été créés pour tous les employés.');
}
}
