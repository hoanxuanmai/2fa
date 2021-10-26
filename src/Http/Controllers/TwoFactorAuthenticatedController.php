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
use Illuminate\Support\Facades\Hash;

class TwoFactorAuthenticatedController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show 2FA Setting form
     */
    public function index()
    {
        $user = Auth::user();
        $google2fa_url = "";
        $secret_key = "";

        if($user->two_factor_enabled == 0 && $user->two_factor_secret){
            $google2fa_url = $user->twoFactorQrCodeSvg();
            $secret_key = HXM2FA::parseSecret($user->two_factor_secret);
        }

        if (is_null($user->two_factor_enabled)) {
            $user->two_factor_enabled = 0;
            $user->save();
        }

        return view('HXM2FA::2fa.index', compact('user', 'secret_key', 'google2fa_url'));
    }

    /**
     * Generate 2FA secret key
     */
    public function generate2faSecret(){
        $user = Auth::user();

        // Add the secret key to the registration data
        $user->two_factor_enabled = 0;
        $user->two_factor_secret = HXM2FA::generate2FASecret();
        $user->save();

        return redirect()->route('HXM2FA.setting')->with('success', __("Secret key is generated."));
    }

    /**
     * Enable 2FA
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable2fa(Request $request){
        $user = Auth::user();

        $secret = $request->input('secret');

        $valid = HXM2FA::verifyCode($user->two_factor_secret, $secret);

        if($valid){
            $user->two_factor_enabled = 1;
            $user->save();
            return redirect()->route('HXM2FA.setting')->with('success', config('hxm2fa.messages.enabled_2fa', __('2FA is enabled successfully.')));
        }else{
            return redirect()->route('HXM2FA.setting')->with('error', config('hxm2fa.messages.invalid_code', __('Invalid verification Code, Please try again.')));
        }
    }

    /**
     * Disable 2FA
     */
    public function disable2fa(Request $request){
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error", config('hxm2fa.messages.password_wrong', __("Your password does not matches with your account password. Please try again.")));
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
        ]);
        $user = Auth::user();
        $user->two_factor_enabled = 0;
        $user->save();
        return redirect()->route('HXM2FA.setting')->with('success', config('hxm2fa.messages.disabled_2fa', __("2FA is now disabled.")));
    }
}
