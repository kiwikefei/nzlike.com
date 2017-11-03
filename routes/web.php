<?php
Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function(){
    App\User::AAA('sdfsdfsf');
});
