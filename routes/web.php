<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/category/{category_slug}', [SearchController::class, 'listingByCategory'])->name('products.category');

// Route::group(['prefix' => env('ADMIN_PREFIX'), 'middleware' => ['guest']], function () {
//     Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
//     Route::post('login', [AuthController::class, 'login']);
// });
