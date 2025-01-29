<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CustomController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Routers to render the views and which is affected by middlewares
Route::get('/', function () {
    return view('home');
});

Route::get('/register', function () {
    return view('sigincustom');
});

Route::get('/login', function () {
    return view('signupcustom');
})->name('login');;

Route::get('/two-auth', function () {
    return view('twoauth');
})
->middleware('haspresession')
;


Route::group(['middleware'=>['hasdoubleauth']],function(){
  
Route::get('/logged', function () {
    return view('logged');
});

Route::get('/logout', [CustomController::class, 'logout']);

});



// Routes where is the logic

Route::post('/register', [CustomController::class, 'register']);
Route::post('/login', [CustomController::class, 'login']);
Route::post('/send-otp', [CustomController::class, 'verifyAuth']);


