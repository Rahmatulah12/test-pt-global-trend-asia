<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->group(['prefix' => 'api'], function() use($router){
    $router->post('register', ['as' => 'register', 'uses' => 'AuthController@registration']);
    $router->post('login', ['as' => 'login', 'uses' => 'AuthController@login']);

    $router->group(['middleware' => 'auth'], function() use($router){
        $router->post("logout", ['as' => 'logout', 'uses' => 'AuthController@logout']);
        $router->get('/', function () use ($router) {
            return $router->app->version();
        });

    });
});
