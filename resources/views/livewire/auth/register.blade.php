<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Créer un compte')" :description="__('Renseignez les champs ci-dessous pour créer votre compte')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="register" class="flex flex-col gap-6">
        <!-- Firstname -->
        <flux:input
            wire:model="firstname"
            :label="__('Prénom(s)')"
            type="text"
            required
            autofocus
            autocomplete="firstname"
            :placeholder="__('Prénom(s)')"
        />

        <!-- Lastname -->
        <flux:input
            wire:model="lastname"
            :label="__('Nom')"
            type="text"
            required
            autofocus
            autocomplete="lastname"
            :placeholder="__('Nom')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Adresse Email')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Mot de passe')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Mot de passe')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirmation mot de passe')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirmation mot de passe')"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Créer un compte') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('Vous avez déjà un compte?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Connectez-vous') }}</flux:link>
    </div>
</div>
