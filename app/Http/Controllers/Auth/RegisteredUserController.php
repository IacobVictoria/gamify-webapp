<?php

namespace App\Http\Controllers\Auth;

use App\Enums\CityRomania;
use App\Enums\Gender;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Auth\Events\Registered;
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
        return Inertia::render('Auth/Register',[
            'locations' => CityRomania::getAllCities(), // Send cities to the frontend
            'genders' => Gender::getAllGenders()  
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $locations = array_column(CityRomania::getAllCities(), 'value');
        $genders = Gender::getAllGenders();

        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => ['required', 'in:' . implode(',', $genders)],
            'birthdate' => 'required|date',
            'location' => ['nullable', 'in:' . implode(',', $locations)],
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'location' => $request->location,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        UserRole::insert([
            'user_id' => $user->id,
            'role_id' => Role::query()->where('name', 'user')->first()->id,
        ]);
        event(new Registered($user));

        Auth::login($user);

        return redirect(route('user.dashboard'));
    }
}
