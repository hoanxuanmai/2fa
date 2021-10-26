<?php
/**
 * Created by HoanXuanMai
 * Email: hoanxuanmai@gmail.com
 */

namespace HXM2FA\Http\Controllers;


use App\Http\Controllers\Auth\LoginController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends LoginController
{
    use AuthenticatesUsers;

    protected function sendLoginResponse(Request $request)
    {
        if (config('hxm2fa.enabled')) {
            if ($request->session()->get('login.id', null)) {
                return redirect()->route('two-factor-challenge.view');
            }

            if (Auth::check() && is_null(Auth::user()->two_factor_enabled)) {
                return redirect()->route('HXM2FA.setting');
            }

        }


        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect()->intended($this->redirectPath());
    }


    protected function attemptLogin(Request $request)
    {
        if (config('hxm2fa.enabled')) {

            $user = $this->guard()->getProvider()->getModel()::where($request->only($this->username()))->first();


            if ($user && $user->two_factor_enabled && $this->guard()->getProvider()->validateCredentials($user, ['password' => $request->password])) {

                $request->session()->put([
                    'login.id' => $user->getKey(),
                    'login.remember' => $request->filled('remember'),
                ]);

                return $user;
            }
        }
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }
}
