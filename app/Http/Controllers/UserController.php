<?php

namespace App\Http\Controllers;

use App\Mail\Welcome;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Spatie\UrlSigner\Laravel\Facades\UrlSigner;

class UserController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::default()]
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        Auth::login($user);

        Mail::to($user)->send(new Welcome(
            UrlSigner::sign(config('app.frontend_url') . '/verifyEmail')
        ));

        return response()->json([
            'id' => $user->id,
            'email' => $request->email
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string']
        ]);

        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();

            $user = User::where('email', $request->email)->first(['id', 'email']);

            return response()->json([
                'id' => $user->id,
                'email' => $request->email,
                'email_verified_at' => $user->email_verified_at,
                'projects' => $user->projects->map(function ($project) {
                    return [
                        'id' => $project->id,
                        'name' => $project->name
                    ];
                })
            ]);
        }

        return response()->json([
            'error' => 'The provided credentials do not match our records.'
        ], 401);
    }

    public function logout(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'emailVerifiedAt' => $user->email_verified_at,
            'projects' => $user->projects->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name
                ];
            })
        ]);
    }
}
