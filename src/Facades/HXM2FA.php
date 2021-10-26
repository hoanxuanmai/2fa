<?php
/**
 * Created by HoanXuanMai
 * Email: hoanxuanmai@gmail.com
 */


namespace HXM2FA\Facades;

/**
 * GG2FA Facade
 *
 * @method static \PragmaRX\Google2FA\Google2FA getGoogle2FA()
 * @method static string|null generate2FASecret()
 * @method static bool|int|null verifyCode(string $secret, string $code)
 * @method static string getQrCode(string $company, string $holder, string $secret)
 * @method static string parseSecret(string $secret)
 * @method static string|null getCurrentCode(string $secret)
 *
 * @see \GG2FA\HXM2FA
 */

class HXM2FA extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return \HXM2FA\HXM2FA::class;
    }
}
