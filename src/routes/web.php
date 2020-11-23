<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Akrad\Bridage\Http\Controllers'],function(){
    
    Route::get('/bridage','BridageController@index')->name('bridage');
    
    Route::post('/bridage','BridageController@send');

    Route::get('/addProject','BridageController@addProject');

    Route::get('/models', 'modelController@getAllModels');

    Route::get('/users', 'userController@getAllUsers');
});
