<?php
/**
 * Created by HoanXuanMai
 * Email: hoanxuanmai@gmail.com
 */

namespace HXM2FA\Http\Controllers;


use HXM2FA\Facades\HXM2FA;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthenticatedSessionController extends Controller
{


    public function view()
    {
        return view('HXM2FA::two-factor-challenge');
    }

    public function store(Request $request)
    {
        $user = Auth::guard()->getProvider()->getModel()::whereId(session()->get('login.id'))->first();
        if ($user && HXM2FA::verifyCode($user->two_factor_secret, $request->code)) {
            Auth::loginUsingId(session()->pull('login.id'), session()->pull('login.remember'));

            $request->session()->regenerate();

            return redirect('/');
        }

        return redirect()->back()->withErrors(config('hxm2fa.messages.invalid_code', __('Invalid verification Code, Please try again.')));
    }
}
