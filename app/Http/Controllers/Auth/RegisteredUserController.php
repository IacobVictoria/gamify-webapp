<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Gender;
use App\Http\Controllers\Controller;
use App\Jobs\SendWhatsappMessageJob;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{

    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register', [
            'genders' => Gender::getAllGenders()
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $genders = Gender::getAllGenders();

        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => ['required', 'in:' . implode(',', $genders)],
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        UserRole::insert([
            'user_id' => $user->id,
            'role_id' => Role::query()->where('name', 'user')->first()->id,
        ]);
        // event(new Registered($user));
        // event(new UserRegistration($user));

        // Trimitere mesaj WhatsApp pentru utilizator nou
        SendWhatsappMessageJob::dispatch( 'new_user', ['name' => $user->name]);

        Auth::login($user);

        return redirect(route('user.dashboard'));
    }
}
