<x-guest-layout>
    <div class="space-y-8">
        <div>
            <p class="forum-eyebrow">Account access</p>
            <h2 class="forum-section-title mt-2">Log in and pick up where you left off.</h2>
            <p class="forum-copy mt-4 text-base leading-7">
                Access your account to read discussions, manage your profile, and stay connected with the community.
            </p>
        </div>

        <x-auth-session-status class="mb-0" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <div class="flex items-center justify-between gap-4">
                    <x-input-label for="password" :value="__('Password')" class="mb-0" />
                    @if (Route::has('password.request'))
                        <a class="forum-link" href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <x-text-input id="password" class="mt-2" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <label for="remember_me" class="flex items-center gap-3 rounded-2xl border px-4 py-3 text-sm" style="border-color: var(--color-border); background: rgba(255,255,255,0.65); color: var(--color-muted);">
                <input id="remember_me" type="checkbox" class="rounded border-[var(--color-border)] text-[var(--color-primary)] focus:ring-[rgba(111,51,29,0.18)]" name="remember">
                <span>{{ __('Keep me signed in on this device') }}</span>
            </label>

            <div class="space-y-3 pt-2">
                <button type="submit" class="forum-btn w-full">
                    {{ __('Log in') }}
                </button>

                <p class="text-center text-sm" style="color: var(--color-muted);">
                    {{ __("Need an account?") }}
                    <a class="forum-link" href="{{ route('register') }}">{{ __('Register here') }}</a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
