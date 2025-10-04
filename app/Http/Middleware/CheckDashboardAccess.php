<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDashboardAccess
{
    /**
     * Vérifier si l'utilisateur a accès au dashboard
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder au dashboard.');
        }

        // Vérifier si l'utilisateur est suspendu
        if ($user->isSuspended()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Votre compte a été suspendu. Contactez l\'administrateur.');
        }

        // Vérifier si l'utilisateur a un rôle autorisé
        $authorizedRoles = [
            'Super Administrateur',
            'Administrateur',
            'Gestionnaire',
            'Caissier',
            'Vendeur',
            'Fournisseur',
        ];

        if (!$user->hasAnyRole($authorizedRoles)) {
            return redirect()->route('home')->with('error', 'Vous n\'avez pas accès au dashboard administrateur.');
        }

        // Enregistrer la connexion
        if (!$request->ajax() && !$request->wantsJson()) {
            $user->recordLogin();
        }

        return $next($request);
    }
}
