<x-guest-layout>
    <div class="space-y-8">
        <div>
            <p class="forum-eyebrow">New member</p>
            <h2 class="forum-section-title mt-2">Create your account and join the conversation.</h2>
            <p class="forum-copy mt-4 text-base leading-7">
                Sign up with your name, email, and password to explore topics, take part in discussions, and build your place in the community.
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
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
                    {{ __('Register') }}
                </button>

                <p class="text-center text-sm" style="color: var(--color-muted);">
                    {{ __('Already registered?') }}
                    <a class="forum-link" href="{{ route('login') }}">{{ __('Log in instead') }}</a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
