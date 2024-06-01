<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\StatutCongeController;
use App\Http\Controllers\DemandeCongeListeController;
use App\Http\Controllers\DemandeCongeDecisionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SuivreDemandeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SoldeController;
use App\Http\Controllers\TelechargerDemandeController;
use App\Http\Controllers\AdminDashController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JourFerieController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/admin/dashboard', [AdminDashController::class, 'index'])->name('admindash');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');


require __DIR__.'/auth.php';

Route::get('/employes', [EmployeController::class, 'index'])->name('employes.index');
Route::get('/employes/create', [EmployeController::class, 'create'])->name('employes.create');
Route::post('/employes', [EmployeController::class, 'store'])->name('employes.store');
Route::get('/employes/{ID}/edit', [EmployeController::class, 'edit'])->name('employes.edit');
Route::put('/employes/{ID}', [EmployeController::class, 'update'])->name('employes.update');
Route::delete('/employes/{ID}', [EmployeController::class, 'destroy'])->name('employes.destroy');


Route::get('/structures', [StructureController::class, 'index'])->name('structures.index');
Route::get('/structures/create', [StructureController::class, 'create'])->name('structures.create');
Route::post('/structures', [StructureController::class, 'store'])->name('structures.store');
Route::get('/structures/{structure}/edit', [StructureController::class, 'edit'])->name('structures.edit');
Route::put('/structures/{structure}', [StructureController::class, 'update'])->name('structures.update');
Route::delete('/structures/{structure}', [StructureController::class, 'destroy'])->name('structures.destroy');


Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
Route::put('roles/{id}/edit', [RoleController::class, 'update']);
Route::get('roles/{id}/delete', [RoleController::class, 'destroy']);


Route::get('/types', [TypeController::class, 'index'])->name('types.index');
Route::get('/types/create', [TypeController::class, 'create'])->name('types.create');
Route::post('/types', [TypeController::class, 'store'])->name('types.store');
Route::get('/types/{id}/edit', [TypeController::class, 'edit'])->name('types.edit');
Route::put('/types/{id}', [TypeController::class, 'update'])->name('types.update');
Route::delete('/types/{id}', [TypeController::class, 'destroy'])->name('types.destroy');

Route::get('/demandes/create', [DemandeController::class, 'create'])->name('demandes.create');
Route::post('/demandes', [DemandeController::class, 'store'])->name('demandes.store');

Route::get('/statutsconges', [StatutCongeController::class, 'index'])->name('statutsconges.index');

Route::get('/listedemandes', [DemandeCongeListeController::class, 'index'])->name('listedemandes.index');
Route::get('/delegated-demandes', [DemandeCongeListeController::class, 'index'])->name('delegated.demandes')->defaults('delegated', true);

Route::get('/demande/{id}', [DemandeCongeDecisionController::class, 'show'])->name('decisiondemande.show');
Route::post('/demandes/decide/{id}', [DemandeCongeDecisionController::class, 'decide'])->name('demandes.decide');

Route::get('/leave-track', [SuivreDemandeController::class, 'track'])->name('leave.track');
Route::get('/leave-request/download/{id}', [TelechargerDemandeController::class, 'download'])->name('leave-request.download');


Route::get('/demande/verify/{id}', function($id) {
    $demande = App\Models\Demande::find($id);
    if ($demande) {
        return view('demandes.verify', compact('demande'));
    } else {
        return "Demande non trouvée";
    }
})->name('demandes.verify');

Route::get('/jours_feries', [JourFerieController::class, 'index'])->name('jours_feries.index');
Route::post('/jours_feries', [JourFerieController::class, 'store'])->name('jours_feries.store');
Route::get('/jours_feries/{id}/edit', [JourFerieController::class, 'edit'])->name('jours_feries.edit');
Route::put('/jours_feries/{id}', [JourFerieController::class, 'update'])->name('jours_feries.update');
Route::delete('/jours_feries/{id}', [JourFerieController::class, 'destroy'])->name('jours_feries.destroy');








Route::delete('notifs/{id}/delete', [NotificationController::class, 'destroy'])->name('notifications.destroy');;

Route::post('/soldes', [SoldeController::class, 'store'])->name('soldes.store');
Route::put('/soldes/{id}', [SoldeController::class, 'update'])->name('soldes.update');

