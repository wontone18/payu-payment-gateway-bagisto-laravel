<?php

Route::group([
    //   'prefix'     => 'payu',
       'middleware' => ['web', 'theme', 'locale', 'currency']
   ], function () {
	   
       Route::get('payu-redirect',"Wontonee\Payu\Http\Controllers\PayuController@redirect")->name('payu.process');
       Route::post('payu-success',"Wontonee\Payu\Http\Controllers\PayuController@success")->name('payu.success');
       Route::post('payu-failure',"Wontonee\Payu\Http\Controllers\PayuController@failure")->name('payu.failure');
     
});