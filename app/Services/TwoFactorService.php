<?php

namespace App\Services;

use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorService
{

    public function enable2FA(Request $request)
    {
        $google2fa = new Google2FA();

        // Generate a new secret key for the user

        // Save the secret key to the user's profile
        $user = $request->user();
        if($user->google2fa_secret == null){
            $secretKey = $google2fa->generateSecretKey();
            $user->google2fa_secret = $secretKey;
            $user->save();
        }
        // Generate the QR Code URL for Google Authenticator
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );
        $secretKey =  $user->google2fa_secret;

        return view('auth.2fa', ['qrCodeUrl' => $qrCodeUrl, 'secretKey' => $secretKey]);
    }

    public function verify2FA(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|numeric',
        ]);

        $google2fa = new Google2FA();

        $user = $request->user();

        $isValid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password);

        if ($isValid) {
            // Mark the user as verified (e.g., set a session or database flag)
            $request->session()->put('2fa_verified', true);

            return redirect()->intended('/home');
        }

        return back()->withErrors(['one_time_password' => 'The provided code is invalid.']);
    }

    public function disable2FA(Request $request)
    {
        $user = $request->user();
        $user->google2fa_secret = null;
        $user->save();

        return redirect('/settings')->with('status', '2FA has been disabled.');
    }
}
