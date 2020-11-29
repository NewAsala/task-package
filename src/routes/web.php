<?php

use Akrad\Bridage\Http\Controllers\modelController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Akrad\Bridage\Http\Controllers'],function(){
    
    Route::get('/bridage','BridageController@index')->name('bridage');
    
    Route::post('/bridage','BridageController@send');

    Route::get('/addProject','BridageController@addProject');

    //Route::get('/users', 'userController@getAllUsers');

    Route::get('/models', 'modelController@getAllModels')->name('models');

    Route::post('/models','modelController@send');

    Route::get('/getAttribute/{path}', 'modelController@getAttribute');
});
