<?php
/**
 * Created by HoanXuanMai
 * Email: hoanxuanmai@gmail.com
 */

use Illuminate\Support\Facades\Route;

Route::group([], function(){
    Route::post('/login', [\HXM2FA\Http\Controllers\AuthController::class, 'login'])->name('login');

    Route::get('/two-factor-challenge', [\HXM2FA\Http\Controllers\TwoFactorAuthenticatedSessionController::class, 'view'])->name('two-factor-challenge.view');
    Route::post('/two-factor-challenge', [\HXM2FA\Http\Controllers\TwoFactorAuthenticatedSessionController::class, 'store'])->name('two-factor-challenge.store');

    Route::group(['prefix' => config('gg2fa.route.prefix', 'user'), 'as' => "HXM2FA."], function(){
        Route::get('2fa', [\HXM2FA\Http\Controllers\TwoFactorAuthenticatedController::class, 'index'])->name('setting');
        Route::post('/generateSecret',[\HXM2FA\Http\Controllers\TwoFactorAuthenticatedController::class, 'generate2faSecret'])->name('generate2faSecret');
        Route::post('/enable2fa',[\HXM2FA\Http\Controllers\TwoFactorAuthenticatedController::class, 'enable2fa'])->name('enable2fa');
        Route::post('/disable2fa',[\HXM2FA\Http\Controllers\TwoFactorAuthenticatedController::class, 'disable2fa'])->name('disable2fa');
    });
});
