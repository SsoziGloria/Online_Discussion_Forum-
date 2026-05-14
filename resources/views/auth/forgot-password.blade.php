<x-guest-layout>
    <div class="space-y-8">
        <div>
            <p class="forum-eyebrow">Recovery</p>
            <h2 class="forum-section-title mt-2">Request a password reset link.</h2>
            <p class="forum-copy mt-4 text-base leading-7">
                Enter the email address linked to your account and we will send you a link to reset your password.
            </p>
        </div>

        <x-auth-session-status class="mb-0" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="space-y-3 pt-2">
                <button type="submit" class="forum-btn w-full">
                    {{ __('Email Password Reset Link') }}
                </button>

                <p class="text-center text-sm" style="color: var(--color-muted);">
                    <a class="forum-link" href="{{ route('login') }}">{{ __('Return to login') }}</a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
