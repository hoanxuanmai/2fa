<?php
/**
 * Created by HoanXuanMai
 * Email: hoanxuanmai@gmail.com
 */

return [
    'enabled' => true,
    'show_secret' => false,
    'route' => [
        'prefix' => 'user'
    ],

    'extend_layout' => 'layouts.app',

    'encrypt_secret' => true,

    /*customer QR style*/
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
];
