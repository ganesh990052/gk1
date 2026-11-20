<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\Api\CashfreeController1;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [SessionsController::class, 'create'])->middleware('guest')->name('login');
Route::get('/home', function () {
    return redirect()->route('login');
});
Route::get('terms-and-conditions', function () {
    return view('terms-and-conditions');
});
Route::get('refund-and-cancellation-policy', function () {
    return view('refund-and-cancellation-policy');
});
Route::get('privacy-policy', function () {
    return view('privacy-policy');
});

Route::get('/payment/callback', [CashfreeController1::class, 'paymentCallback']);
Route::get('/payment/success', [CashfreeController1::class, 'paymentSuccess']);

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');

    return "✅ All caches cleared successfully!";
});