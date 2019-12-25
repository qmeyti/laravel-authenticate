<?php

Route::group(['middleware' => ['web']], function () {
    Route::resource('signup', 'Qmeyti\LaravelAuth\Controllers\SignupController');

    Route::get('page/verification', 'Qmeyti\LaravelAuth\Controllers\VerificationController@verification_form')->name('verification_form');
    Route::get('verification', 'Qmeyti\LaravelAuth\Controllers\VerificationController@verification')->name('verification');
    Route::get('resend_code', 'Qmeyti\LaravelAuth\Controllers\VerificationController@resend_code')->name('resend_code');

    Route::get('signin', 'Qmeyti\LaravelAuth\Controllers\SigninController@signin_form')->name('signin_form');
    Route::post('signin', 'Qmeyti\LaravelAuth\Controllers\SigninController@signin')->name('signin');

    Route::post('signout', 'Qmeyti\LaravelAuth\Controllers\SigninController@signout')->name('signout');

    Route::get('recovery', 'Qmeyti\LaravelAuth\Controllers\RecoveryPasswordController@recovery_form')->name('recovery_form');
    Route::post('recovery', 'Qmeyti\LaravelAuth\Controllers\RecoveryPasswordController@recovery')->name('recovery');
    Route::get('recovery/message', 'Qmeyti\LaravelAuth\Controllers\RecoveryPasswordController@recovery_message')->name('recovery_message');
    Route::get('recovery/reset', 'Qmeyti\LaravelAuth\Controllers\RecoveryPasswordController@recovery_reset_form')->name('recovery_reset_form');
    Route::post('recovery/reset', 'Qmeyti\LaravelAuth\Controllers\RecoveryPasswordController@recovery_reset')->name('recovery_reset');

});