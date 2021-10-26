<?php
/**
 * Created by HoanXuanMai
 * Email: hoanxuanmai@gmail.com
 */

namespace HXM2FA;

use HXM2FA\Facades\HXM2FA;

trait TwoFactorAuthenticatable
{
    /**
     * Get the QR code SVG of the user's two factor authentication QR code URL.
     *
     * @return string
     */
    public function twoFactorQrCodeSvg()
    {
        return HXM2FA::getQrCode(
            config('app.name'),
            $this->email,
            $this->two_factor_secret
        );
    }

}
