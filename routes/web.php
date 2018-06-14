<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web"(guest) middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');



//Auth::routes();
//User Auth group
Route::group(['namespace' => 'Auth\\', 'prefix' => 'user' ], 
	function() {
        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login');
        Route::post('logout', 'LoginController@logout')->name('logout');

        // Registration Routes...
        Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'RegisterController@register');

        // Password Reset Routes...
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'ResetPasswordController@reset');

        Route::get('/verify/{email}/{token}', 'RegisterController@verify')->name('verify.user');

});

//Admin Auth group
Route::group(['as'=>'admin.', 'namespace' => 'Auth\\', 'prefix' => 'admin', ], function() {


Route::get('/login', 'AdminLoginController@showLoginForm')->name('login');

Route::post('/login', 'AdminLoginController@login')->name('login.save');

Route::post('/logout', 'AdminLoginController@logout')->name('logout');


//password reset routes
Route::post('password/email', 'AdminForgotPasswordController@sendResetLinkEmail')->name('password.email');

Route::get('password/reset', 'AdminForgotPasswordController@showLinkRequestForm')->name('password.request');

Route::post('password/reset', 'AdminResetPasswordController@reset');

Route::get('password/reset/{token}', 'AdminResetPasswordController@showResetForm')->name('password.reset');



});



/* Admin Group 
| route name prefixed with 'admin' it producrs admin.GIVENROUTENAME

| route url prefixed with 'admin' it producrs admin/GIVENURLNAME

| route middleware with two authentication guards as 'web' and 'admin'

| controller prefixed with namespace 'Admin' for that namespaced   
   controller
*/

Route::group(['as'=>'admin.', 'namespace' => 'Admin\\', 'prefix' => 'admin/', 'middleware' => ['auth:admin'] ], function() {

Route::get('/admin-dasboard', 'DashboardController@index')->name('dashboard');
	
});



Route::group(['as'=>'user.', 'namespace' => 'User\\', 'prefix' => 'user/', 'middleware' => ['auth'] ], function() {

Route::get('/user-dasboard', 'DashboardController@index')->name('dashboard');
	
});


