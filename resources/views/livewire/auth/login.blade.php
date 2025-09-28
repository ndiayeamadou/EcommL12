<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Connectez-vous à votre compte')" :description="__('Entrez votre email et mot de passe ci-dessous pour vous connecter')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Adresse Email')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Mot de passe')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Mot de passe')"
                viewable
            />

            {{-- @if (Route::has('password.request'))
                <flux:link class="absolute end-0 top-0 text-sm" :href="route('password.request')" wire:navigate>
                    {{ __('Mot de passe oublié?') }}
                </flux:link>
            @endif --}}
        </div>

        <!-- Remember Me -->
        {{-- <flux:checkbox wire:model="remember" :label="__('Se souvenir de moi')" /> --}}

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="cursor-pointer w-full">{{ __('Me connecter') }}</flux:button>
        </div>
    </form>

    {{-- @if (Route::has('register'))
        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Vous n\'avez pas de compte?') }}</span>
            <flux:link :href="route('register')" wire:navigate>{{ __('S\'inscrire') }}</flux:link>
        </div>
    @endif --}}
</div>
