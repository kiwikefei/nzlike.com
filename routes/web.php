<?php

use App\Services\Deployment\Deployment;
use App\Services\Deployment\WebsiteDeployed;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function(){
    (new Deployment)->notify(new WebsiteDeployed("HHHHFDKSFJ"));
});
