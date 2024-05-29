<?php 
namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NavigationComposer
{
    public function compose(View $view)
    {
        $user = Auth::user();
        $activeDelegationRoles = 0;

        if ($user && $user->employe) {
            $employe = $user->employe;
            $today = Carbon::today()->format('Y-m-d');

            // Fetch active delegation roles
            $activeDelegationRoles = $employe->delegationRoles()
                ->where('DATE_DEBUT', '<=', $today)
                ->where('DATE_FIN', '>=', $today)
                ->count();
        }

        $view->with('activeDelegationRoles', $activeDelegationRoles);
    }
}