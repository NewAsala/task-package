<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Akrad\Bridage\Http\Controllers'],function(){
    Route::group(['middleware' => 'web'], function () {

    Route::get('/assignes', 'userController@getAllAssignee');
    Route::get('/createAssigne', 'userController@createAssigne')->name('createAssigne');
    Route::post('/addAssigne', 'userController@storeAssigneeAndUpdate')->name('addAssigne');

    Route::get('/func', 'ActionController@create')->name('getfunc');
    Route::post('/storeFunc','ActionController@store')->name('func');
    Route::post('/getParameter', 'ActionController@getParameter');

    Route::get('/models', 'helperController@create')->name('getModels');
    Route::post('/models','helperController@send')->name('models');
    Route::post('/getAttribute', 'helperController@getAttribute')->name('getAttribute');

    Route::post('/send-email', 'MailController@sendEmail')->name('sendEmail');
    Route::post('/send-SMS', 'MailController@sendSMS')->name('sendSMS');

    });
});
