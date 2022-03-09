<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'SRA\Passport\Http\Controllers',
    'prefix' => 'api/passport',
    'middleware' => ['auth:sanctum', 'ability:admin']
], function () {
    Route::post("list", "PassportController@list")
        ->middleware(["has.token"])->name("passport.list");
    Route::post("enable_list", "PassportController@enable_list")
        ->middleware(["has.token"])->name("passport.enable.list");
    Route::post("revoke", "PassportController@revoke")->name("passport.revoke");
    Route::post("create", "PassportController@create")->name("passport.create");
});

Route::group([
    'namespace' => 'SRA\Passport\Http\Controllers',
    'prefix' => 'api/passport',
    'middleware' => ['api']
], function () {
    Route::post("refresh", "PassportController@refresh")->name("passport.refresh");
});
