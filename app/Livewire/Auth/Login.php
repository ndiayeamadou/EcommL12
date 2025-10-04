<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    /* #[Validate('required|string|email')]
    public string $email = ''; */
    #[Validate('required|string')]
    public string $authIdentifier = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login()
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        //  Determine login type (email, username or phone number)
        $login_type = 'username';

        if(filter_var($this->authIdentifier, FILTER_VALIDATE_EMAIL)) {
            $login_type = 'email';
        }/*  elseif(preg_match('/^\d{9}$/', $this->authIdentifier)) {
            $login_type = 'phone';
        } */

        //if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
        if (! Auth::attempt([$login_type => $this->authIdentifier, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                //'email' => __('auth.failed'),
                'authIdentifier' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        if(auth()->user()->hasRole('Super Administrateur')) {
            return redirect()->route('admin.super-admin-dashboard')->with('success', 'Logged in successfully.');
        }

        //$this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        $this->redirectIntended(default: route('admin.standard-dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            //'email' => __('auth.throttle', [
            'authIdentifier' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        //return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
        return Str::transliterate(Str::lower($this->authIdentifier).'|'.request()->ip());
    }
}
