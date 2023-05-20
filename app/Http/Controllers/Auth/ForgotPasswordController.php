<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $user = User::where('email', $request->get('email'))->first();

        if ($user) {
            $token = Password::broker()->createToken($user);
            $url = config('app.frontend_url') . '/reset-password?email=' . $request->get('email') . '&token=' . $token;

            Mail::to($user)->send(new PasswordReset($url));
        }
    }
}
