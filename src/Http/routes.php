<?php

use Illuminate\Support\Facades\Route;
use Wontonee\Payu\Http\Controllers\PayuController;

Route::group([
    //   'prefix'     => 'payu',
    'middleware' => ['web', 'theme', 'locale', 'currency']
], function () {

    Route::get('payu-redirect', [PayuController::class, 'redirect'])->name('payu.process');
    Route::post('payu-success', [PayuController::class, 'success'])->name('payu.success');
    Route::post('payu-failure', [PayuController::class, 'failure'])->name('payu.failure');
});
