<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')

        <!-- CSS alertifyjs -->
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css"/>
        <!-- Default theme -->
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/default.min.css"/>

    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <!-- Logo avec lien conditionnel -->
            <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                @if (auth()->user()->hasRole('Super Administrateur'))
                    <a href="{{ route('admin.super-admin-dashboard') }}" class="flex items-center space-x-3" wire:navigate>
                        <x-app-logo />
                        {{-- <span class="text-lg font-bold text-zinc-900 dark:text-white">Super Admin</span> --}}
                    </a>
                @else
                    <a href="{{ route('admin.standard-dashboard') }}" class="flex items-center space-x-3" wire:navigate>
                        <x-app-logo />
                        {{-- <span class="text-lg font-bold text-zinc-900 dark:text-white">Dashboard</span> --}}
                    </a>
                @endif
            </div>

            <!-- Navigation Principale -->
            <flux:navlist variant="outline" class="mt-4">
                <flux:navlist.group :heading="__('Tableaux de Bord')" class="grid">
                    @if (auth()->user()->hasRole('Super Administrateur'))
                        <flux:navlist.item 
                            icon="chart-bar" 
                            :href="route('admin.super-admin-dashboard')" 
                            :current="request()->routeIs('super-admin-dashboard')" 
                            wire:navigate>
                            {{ __('Super Dashboard') }}
                        </flux:navlist.item>
                    @else
                        <flux:navlist.item 
                            icon="chart-bar" 
                            :href="route('admin.standard-dashboard')" 
                            :current="request()->routeIs('standard-dashboard')" 
                            wire:navigate>
                            {{ __('Mon Dashboard') }}
                        </flux:navlist.item>
                    @endif
                </flux:navlist.group>
            </flux:navlist>

            <!-- Gestion Commerciale (Admin/Vendeur/Caissier) -->
            @if (Auth::user()->type == 3)
            <flux:navlist variant="outline" class="mt-4">
                <flux:navlist.group :heading="__('Gestion Commerciale')" class="grid">
                    <flux:navlist.item 
                        icon="rectangle-stack" 
                        :href="route('admin.categories')" 
                        :current="request()->routeIs('admin.categories')" 
                        wire:navigate>
                        {{ __('Catégories') }}
                    </flux:navlist.item>
                    
                    <flux:navlist.item 
                        icon="cube" 
                        :href="route('admin.products.index')" 
                        :current="request()->routeIs('admin.products.index')" 
                        wire:navigate>
                        {{ __('Produits') }}
                    </flux:navlist.item>
                    
                    <flux:navlist.item 
                        icon="shopping-cart" 
                        :href="route('admin.pos.sales')" 
                        :current="request()->routeIs('admin.pos.sales')" 
                        wire:navigate>
                        {{ __('Point de Vente') }}
                    </flux:navlist.item>
                    
                    <flux:navlist.item 
                        icon="clipboard-document-list" 
                        :href="route('admin.orders.index')" 
                        :current="request()->routeIs('admin.orders.index')" 
                        wire:navigate>
                        {{ __('Commandes') }}
                    </flux:navlist.item>
                    
                    <flux:navlist.item 
                        icon="users" 
                        :href="route('admin.customers.index')" 
                        :current="request()->routeIs('admin.customers.index')" 
                        wire:navigate>
                        {{ __('Clients') }}
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
            @endif

            <!-- Gestion Administrative (Super Admin) -->
            @if (Auth::user()->type == 3 && auth()->user()->hasRole('Super Administrateur'))
            <flux:navlist variant="outline" class="mt-4">
                <flux:navlist.group :heading="__('Administration')" class="grid">
                    @can('manage_system_users')
                    <flux:navlist.item 
                        icon="user-group" 
                        :href="route('admin.users.index')" 
                        :current="request()->routeIs('admin.users.index')" 
                        wire:navigate>
                        {{ __('Utilisateurs') }}
                    </flux:navlist.item>
                    @endcan
                    
                    {{-- <flux:navlist.item 
                        icon="chart-pie" 
                        :href="route('admin.reports')" 
                        :current="request()->routeIs('admin.reports')" 
                        wire:navigate>
                        {{ __('Rapports') }}
                    </flux:navlist.item> --}}
                    
                    {{-- <flux:navlist.item 
                        icon="cog-6-tooth" 
                        :href="route('settings.profile')" 
                        :current="request()->routeIs('settings.profile')" 
                        wire:navigate>
                        {{ __('Paramètres Système') }}
                    </flux:navlist.item> --}}
                </flux:navlist.group>
            </flux:navlist>
            @endif

            <!-- Statistiques Rapides (Optionnel) -->
            {{-- @if (Auth::user()->type == 3)
            <div class="mt-6 px-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Aperçu Rapide
                    </h4>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between text-blue-700 dark:text-blue-300">
                            <span>Commandes Aujourd'hui:</span>
                            <span class="font-semibold"><!-- $todayOrders --> 12</span>
                        </div>
                        <div class="flex justify-between text-green-700 dark:text-green-300">
                            <span>CA du jour:</span>
                            <span class="font-semibold"><!-- number_format($todayRevenue, 0, ',', ' ') --> 245 000 F</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif --}}

            {{-- <flux:spacer /> --}}

            <!-- Menu Utilisateur -->
            {{-- <flux:navlist variant="outline" class="mt-auto">
                <flux:navlist.item 
                    icon="cog-6-tooth" 
                    :href="route('settings.profile')" 
                    :current="request()->routeIs('settings.profile')" 
                    wire:navigate>
                    {{ __('Mon Compte') }}
                </flux:navlist.item>
                
                <!-- Documentation et Support -->
                <flux:navlist.item 
                    icon="question-mark-circle" 
                    href="https://docs.votre-app.com" 
                    target="_blank">
                    {{ __('Aide & Support') }}
                </flux:navlist.item>
            </flux:navlist> --}}

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block mt-4" position="top" align="start">
                <flux:profile
                    :name="auth()->user()->firstname . ' ' . auth()->user()->lastname"
                    :description="auth()->user()->getRoleNames()->first() ?? 'Utilisateur'"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevron-down"
                    class="w-full"
                />

                <flux:menu class="w-[240px]">
                    <flux:menu.radio.group>
                        <div class="p-2">
                            <div class="flex items-center gap-3">
                                <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-lg bg-gradient-to-br from-blue-500 to-purple-600">
                                    <span class="flex h-full w-full items-center justify-center text-white font-semibold text-sm">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold text-zinc-900 dark:text-white">
                                        {{ auth()->user()->firstname . ' ' . auth()->user()->lastname }}
                                    </span>
                                    <span class="truncate text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ auth()->user()->email }}
                                    </span>
                                    <span class="truncate text-xs text-blue-600 dark:text-blue-400 font-medium">
                                        {{ auth()->user()->getRoleNames()->first() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="user" wire:navigate>
                            {{ __('Mon Profil') }}
                        </flux:menu.item>
                        {{-- <flux:menu.item :href="route('settings.security')" icon="shield-check" wire:navigate>
                            {{ __('Sécurité') }}
                        </flux:menu.item> --}}
                        {{-- <flux:menu.item :href="route('settings.notifications')" icon="bell" wire:navigate>
                            {{ __('Notifications') }}
                        </flux:menu.item> --}}
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="cursor-pointer w-full text-red-600 hover:text-red-700">
                            {{ __('Déconnexion') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden border-b border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-3" inset="left" />

            <div class="flex-1 text-center">
                <span class="text-lg font-bold text-zinc-900 dark:text-white">
                    @if (auth()->user()->hasRole('Super Administrateur'))
                        Super Admin
                    @else
                        {{ auth()->user()->getRoleNames()->first() }}
                    @endif
                </span>
            </div>

            <flux:dropdown position="bottom" align="end">
                <flux:button
                    variant="outline"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu class="w-64">
                    <flux:menu.radio.group>
                        <div class="p-3">
                            <div class="flex items-center gap-3">
                                <span class="relative flex h-12 w-12 shrink-0 overflow-hidden rounded-lg bg-gradient-to-br from-blue-500 to-purple-600">
                                    <span class="flex h-full w-full items-center justify-center text-white font-semibold">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start leading-tight">
                                    <span class="truncate font-semibold text-zinc-900 dark:text-white">
                                        {{ auth()->user()->firstname . ' ' . auth()->user()->lastname }}
                                    </span>
                                    <span class="truncate text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ auth()->user()->email }}
                                    </span>
                                    <span class="truncate text-sm text-blue-600 dark:text-blue-400 font-medium">
                                        {{ auth()->user()->getRoleNames()->first() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="user" wire:navigate>
                            {{ __('Mon Profil') }}
                        </flux:menu.item>
                        {{-- <flux:menu.item :href="route('settings.security')" icon="shield-check" wire:navigate>
                            {{ __('Sécurité') }}
                        </flux:menu.item> --}}
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-red-600 hover:text-red-700">
                            {{ __('Déconnexion') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}
        
        <!-- JavaScript Alertifyjs -->
        <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                window.addEventListener('notify', event => {
                    alertify.set('notifier','position', event.detail[0].position || 'top-right');
                    alertify.notify(event.detail[0].text, event.detail[0].type || 'success');
                });
            });
        </script>

        @fluxScripts
    </body>
</html>