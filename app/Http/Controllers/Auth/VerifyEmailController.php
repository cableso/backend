<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Welcome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\UrlSigner\Laravel\Facades\UrlSigner;

class VerifyEmailController extends Controller
{
    public function store(Request $request)
    {
        if ($request->get('signature')) {
            // Check verification signature
            $url = config('app.frontend_url') . '/verifyEmail?expires='.
                $request->get('expires') . '&signature=' . $request->get('signature');

            if (! UrlSigner::validate($url)) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }

            if (! $request->user()->email_verified_at) {
                $request->user()->email_verified_at = now();
                $request->user()->save();
            }
        } else {
            // Re-send verification email
            Mail::to($request->user())->send(new Welcome(
                UrlSigner::sign(config('app.frontend_url') . '/verifyEmail')
            ));
        }
    }
}
