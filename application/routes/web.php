<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//Auth Controller routes
$router->group(['prefix' => 'auth'], function () use ($router) {
    //Authenticate user
    $router->post(
        '/',
        [
            'uses' => 'AuthController@authenticate'
        ]
    );
});

//Auth Middleware Group
$router->group(['middleware' => 'auth'], function () use ($router) {

    //User Controller group
    $router->group(['prefix' => 'user'], function () use ($router) {
        //get user
        $router->get(
            '/',
            [
                'uses' => 'UserController@getUser'
            ]
        );
        //get user products
        $router->get(
            '/products',
            [
                'uses' => 'UserController@getUserProducts'
            ]
        );
        //sync user products
        $router->post(
            '/products',
            [
                'uses' => 'UserController@syncUserProducts'
            ]
        );
        //delete user product
        $router->delete(
            '/products/{sku}',
            [
                'uses' => 'UserController@removeUserProduct'
            ]
        );
    });

    //Product Controller group
    $router->group(['prefix' => 'product'], function () use ($router) {
        //Authenticate user
        $router->get(
            '/',
            [
                'uses' => 'ProductController@list'
            ]
        );
    });
});



