<x-guest-layout>
    <div class="space-y-8">
        <div>
            <p class="forum-eyebrow">Recovery confirmation</p>
            <h2 class="forum-section-title mt-2">Choose a new password.</h2>
            <p class="forum-copy mt-4 text-base leading-7">
                Choose a new password for your account, then return to the forum and sign in again.
            </p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div class="space-y-3 pt-2">
                <button type="submit" class="forum-btn w-full">
                    {{ __('Reset Password') }}
                </button>

                <p class="text-center text-sm" style="color: var(--color-muted);">
                    <a class="forum-link" href="{{ route('login') }}">{{ __('Back to login') }}</a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
