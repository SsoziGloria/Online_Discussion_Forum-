<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $name = trim($request->string('name')->toString());
        $username = $this->generateUsername($name);

        $user = User::create([
            'name' => $name,
            'username' => $username,
            'display_name' => $name,
            'email' => $request->string('email')->toString(),
            'avatar_url' => $this->generateAvatarUrl(),
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    private function generateUsername(string $name): string
    {
        $baseUsername = Str::of($name)
            ->lower()
            ->slug(separator: '_')
            ->limit(20, '')
            ->trim('_')
            ->toString();

        if ($baseUsername === '') {
            $baseUsername = 'user';
        }

        $username = $baseUsername;
        $suffix = 1;

        while (User::where('username', $username)->exists()) {
            $username = Str::limit($baseUsername, 20 - strlen((string) $suffix) - 1, '').'_'.$suffix;
            $suffix++;
        }

        return $username;
    }

    private function generateAvatarUrl(): string
    {
        return 'https://api.dicebear.com/9.x/thumbs/svg?seed='.urlencode(Str::uuid()->toString());
    }
}
