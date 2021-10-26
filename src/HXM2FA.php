<?php
/**
 * Created by HoanXuanMai
 * Email: hoanxuanmai@gmail.com
 */

namespace HXM2FA;

use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class HXM2FA
{
    protected $google2fa;
    protected $encryptSecret = true;

    public function __construct()
    {
        $google2fa = (new \PragmaRX\Google2FA\Google2FA());

        $this->google2fa = $google2fa;

        $this->encryptSecret = config('gg2fa.encrypt_secret', true);
    }


    /**
     * @return \PragmaRX\Google2FA\Google2FA
     */
    function getGoogle2FA()
    {
        return $this->google2fa;
    }

    /**
     * @return string|null
     */
    function generate2FASecret()
    {
        try {
            return $this->encryptSecret ? encrypt($this->google2fa->generateSecretKey()): $this->google2fa->generateSecretKey();

        } catch (\Exception $exception) {
            return null;
        }

    }

    /**
     * @param string $secret
     * @param string $code
     * @return bool|int
     /* @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     /* @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     /* @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     */
    function verifyCode(string $secret, string $code)
    {
        try {

            return $this->google2fa->verifyKey($this->parseSecret($secret), $code);
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * @param string $company
     * @param string $holder
     * @param string $secret
     * @return string
     */
    function getQrCode(string $company, string $holder, string $secret)
    {
        $style = config('hxm2fa.qr_code', []);

        $svg = (new Writer(
            new ImageRenderer(
                new RendererStyle(
                    (int)($style['size'] ?? null),
                    (int)($style['margin'] ?? null),
                    null,
                    null,
                    Fill::uniformColor(
                        new Rgb(
                            (int)($style['background']['red'] ?? null),
                            (int)($style['background']['green'] ?? null),
                            (int)($style['background']['blue'] ?? null)
                        ),
                        new Rgb(
                            (int)($style['fill']['red'] ?? null),
                            (int)($style['fill']['green'] ?? null),
                            (int)($style['fill']['blue'] ?? null)
                        )
                    )
                ),
                new SvgImageBackEnd
            )
        ))->writeString($this->google2fa->getQRCodeUrl($company, $holder, $this->parseSecret($secret)));

        return trim(substr($svg, strpos($svg, "\n") + 1));
    }

    /**
     * @param string $secret
     * @return mixed|string
     */
    public function parseSecret(string $secret)
    {
        return $this->encryptSecret ? decrypt($secret) : $secret;
    }

    /**
     * @param string $secret
     * @return string|null
     */
    public function getCurrentCode(string $secret)
    {
        try {
            return $this->google2fa->getCurrentOtp($this->parseSecret($secret));
        } catch (\Exception $exception) {
            return null;
        }

    }
}
