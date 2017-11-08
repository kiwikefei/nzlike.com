<?php
Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function(){
//    $users = \DB::table('users')->get();
    // $users = App\User::whereIn('id', [1,2,3])->get();
    // return $users;
//    return App\User::hydrate($users->toArray());
    session()->put('ttt', 'abc');
});
Route::get('/test2', function(){
    return session()->get('ttt');
});