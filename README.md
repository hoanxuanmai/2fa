##2FA for Laravel

A One Time Password Authentication package.


## Installation

1. This package publishes a config/hxm2fa.php file. If you already have a file by that name, you must rename or remove it.

2. You can install the package via composer:

    ```bash
    composer require hxm/2fa
    ```
3. Run the migrations:

    ```bash
    php artisan migrate
    ```
4. You should publish the template views and the config/hxm2fa.php config file with:
    ```bash
    php artisan vendor:publish --provider=HXM2FA\ServiceProvider
    
    ```

5. Default config file contents
    ```php
    'enabled' => true,
        'show_secret' => true,
        'route' => [
            'prefix' => 'user'
        ],
    
        'extend_layout' => 'layouts.app',
    
        'encrypt_secret' => true,
    
        'qr_code' => [
            'size' => 192,
            'margin' => 0,
            'background' => [
                'red' => 255, //red the red amount of the color, 0 to 255
                'green' => 255, //green the green amount of the color, 0 to 255
                'blue' => 255 //blue the blue amount of the color, 0 to 255
            ],
            'fill' => [
                'red' => 45, //red the red amount of the color, 0 to 255
                'green' => 55, //green the green amount of the color, 0 to 255
                'blue' => 72 //blue the blue amount of the color, 0 to 255
            ],
        ]
    ```
## Basic Usage
* First, add the HXM2FA\TwoFactorAuthenticatable trait to your User model(s):

    ```php
      
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use HXM2FA\TwoFactorAuthenticatable;
    
    class User extends Authenticatable
    {
      use TwoFactorAuthenticatable;
    
      // ...
    }
          
    ```

* We provide a Facede with static functions that perform useful functions:
    
    ```php
          
    use HXM2FA\Facades\HXM2FA;
    
      // ...
    
      /*to generate Secret*/
      $secret = HXM2FA::generate2FASecret();
    
      
      /*to generate QrCode html*/
      HXM2FA::getQrCode(string $company, string $holder, string $secret)
      
      /*to verify code*/
      HXM2FA::verifyCode(string $secret, string $code)
    
      /*To get instance of \PragmaRX\Google2FA\Google2FA */
      HXM2FA::getGoogle2FA();
    
      // ...
          
    ```
## Author
[HoanXuanMai](https://github.com/hoanxuanmai)
