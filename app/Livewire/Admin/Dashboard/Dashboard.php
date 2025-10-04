<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        // Super Administrateur - Dashboard avancé
        if ($user->hasRole('Super Administrateur')) {
            // Retourne le COMPOSANT SuperAdminDashboard, pas la vue
            return app()->make(SuperAdminDashboard::class)->render();
            //return redirect()->route('super-admin-dashboard');
        }

        // Administrateur, Gestionnaire, Caissier, Vendeur - Dashboard standard
        if ($user->hasAnyRole(['Administrateur', 'Gestionnaire', 'Caissier', 'Vendeur'])) {
            /* return view('livewire.admin.dashboard.standard-dashboard')
                ->layout('layouts.app'); */
                //->layout('layouts.admin');
                //return redirect()->route('standard-dashboard');
                return app()->make(StandardDashboard::class)->render();
        }

        // Fournisseur - Dashboard fournisseur
        if ($user->hasRole('Fournisseur')) {
            return view('livewire.admin.dashboard.provider-dashboard')
                ->layout('layouts.app');
                //->layout('layouts.admin');
        }

        // Par défaut, rediriger vers la page d'accueil
        return redirect()->route('home')->with('error', 'Accès non autorisé.');
    }
}
