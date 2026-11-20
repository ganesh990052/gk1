<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\UserBankDetail\BankDetailController;
use App\Http\Controllers\Address\CurrentAddressController;
use App\Http\Controllers\Document\DocumentController;
use App\Http\Controllers\Occupation\OccupationDetailController;
use App\Http\Controllers\NomineeDetail\NomineeDetailController;
use App\Http\Controllers\Admin\Chit\ChitController;
use App\Http\Controllers\Admin\WalletController;

//--------------Chit----------------

Route::get('chit',[ChitController::class,'index'])->name('chit-index')->middleware('auth');
Route::post('chit/add',[ChitController::class, 'store'])->middleware('auth');
Route::get('chit/edit/{id}', [ChitController::class, 'edit'])->name('chit-edit')->middleware('auth');
Route::post('chit/update/{id}', [ChitController::class, 'update'])->name('chite-update')->middleware('auth');
Route::get('chit/delete/{id}', [ChitController::class, 'destroy'])->name('chit.delete')->middleware('auth');
Route::get('chit/table/{id}',[ChitController::class,'show'])->name('chit.table')->middleware('auth');
Route::get('chit/lucky/{id}',[ChitController::class,'group'])->name('chit.lucky')->middleware('auth');
Route::post('group',[ChitController::class,'select_group'])->name('group')->middleware('auth');
Route::post('winner',[ChitController::class,'winner'])->name('winner')->middleware('auth');
Route::get('chit-restore',[ChitController::class,'chit_restore'])->name('chit-restore')->middleware('auth');
Route::patch('/chit-restore/{id}', [ChitController::class, 'restore'])->name('chit.restore');

// password reset 

Route::middleware('auth')->group(function () {
    Route::get('/change-password', [DashboardController::class, 'show'])
        ->name('password.change');

    Route::post('/change-password', [DashboardController::class, 'update'])
        ->name('password.reset.update');
});

// User Details work Start

Route::get('user-details',[RegisterController::class,'user_details'])->name('user-details')->middleware('auth');
// Route::get('/user-details-complit/{id}', [RegisterController::class, 'user_details_complit'])->middleware('auth')->name('user.details');
Route::middleware(['auth'])->group(function () {

    Route::get(
    'chite-fund/emi-details/{user_id}',
    [RegisterController::class, 'emiDetails']
)->name('emi.details');

    Route::get(
    'chite-fund/chit-details/{user_id}',
    [RegisterController::class, 'ChitDetails']
)->name('chit.details');


});
Route::delete('/applied-chit/{id}', [RegisterController::class, 'deleteAppliedChit'])
    ->name('applied.chit.delete');


Route::post(
    'admin/pay-emi',
    [RegisterController::class, 'payEmiByAdmin']
)->name('admin.pay.emi');

Route::patch('chite-fund/applied-chit-deactivate/{id}',
    [RegisterController::class, 'deactivateAppliedChit']
)->name('applied.chit.deactivate');

Route::patch('chite-fund/applied-chit-activate/{id}',
    [RegisterController::class, 'activateAppliedChit']
)->name('applied.chit.activate');

// monthly winner
Route::get('chit/winner/{id}',[ChitController::class,'monthlyWinner'])->name('chit.winner')->middleware('auth');
Route::post('/monthly-lucky-member',[ChitController::class,'monthlyLuckyWinner'])->middleware('auth');
Route::get('/delete-monthly-winner/{id}', [ChitController::class, 'monthlyWinnerDelete'])->name('chit.delete')->middleware('auth');

//---------------Chit End-----------
//---------------Transtion----------
Route::get('user-management',[RegisterController::class,'user_mangengment'])->name('user-management')->middleware('auth');
Route::get('/user-bank-detail/{id}', [BankDetailController::class, 'get_by_user'])->name('chite-fund.getBankDetail')->middleware('auth');
Route::any('transer-mony/',[WalletController::class,'update'])->middleware('auth');
//---------------End Transtion------
//User bank Detalis Route Start 
Route::get('user-bank-detalis', [BankDetailController::class,'index'])->name('bank-index')->middleware('auth');
Route::get('/get-all-users',[BankDetailController::class, 'getAllUsers'])->middleware('auth');
Route::post('/add-bank-detail',[BankDetailController::class, 'addBankDetail'])->middleware('auth');
Route::get('/get-bank-detail/{id}', [BankDetailController::class, 'edit'])->name('chite-fund.getBankDetail')->middleware('auth');
Route::post('/update-bank-detail/{id}', [BankDetailController::class, 'update'])->name('chite-fund.updateBankDetail')->middleware('auth');
Route::get('/delete-bank-detail/{id}', [BankDetailController::class, 'destroy'])->name('bank-detail.delete')->middleware('auth');
//User bank Detalis End
//User address
Route::get('/get-all-address-users',[CurrentAddressController::class, 'getAllUsers'])->middleware('auth');
Route::post('/add-address-detail',[CurrentAddressController::class, 'store']);
Route::get('/delete-address-detail/{id}', [CurrentAddressController::class, 'destroy'])->name('address.delete')->middleware('auth');

Route::get('user-address-detalis',[CurrentAddressController::class,'index'])->name('address.index')->middleware('auth');
Route::get('/get-address-detail/{id}', [CurrentAddressController::class, 'edit'])->name('address.edit')->middleware('auth');
Route::post('/update-address-detail/{id}', [CurrentAddressController::class, 'update'])->name('address.updateaddDetail')->middleware('auth');

//User End address
//User Document

Route::get('user-document-detalis',[DocumentController::class,'index'])->name('document.index')->middleware('auth');
Route::get('/get-all-document-users',[DocumentController::class, 'getAllUsers'])->middleware('auth');
Route::post('/add-document-detail',[DocumentController::class, 'store']);
Route::get('/get-document-detail/{id}', [DocumentController::class, 'edit'])->name('document.edit')->middleware('auth');
Route::post('/update-document-detail/{id}', [DocumentController::class, 'update'])->name('document.update')->middleware('auth');
Route::get('/delete-document-detail/{id}', [DocumentController::class, 'destroy'])->name('document.delete')->middleware('auth');


//End User Document

//Occupation 
Route::get('user-occupation-detalis',[OccupationDetailController::class,'index'])->name('occupation.index')->middleware('auth');
Route::post('/add-occupation-detail',[OccupationDetailController::class, 'store']);
Route::get('/get-all-occupation-users',[OccupationDetailController::class, 'getAllUsers']);
Route::get('/get-occupation-detail/{id}', [OccupationDetailController::class, 'edit'])->name('occupation.edit')->middleware('auth');
Route::post('/update-occupation-detail/{id}', [OccupationDetailController::class, 'update'])->name('occupation.update')->middleware('auth');
Route::get('/delete-occupation-detail/{id}', [OccupationDetailController::class, 'destroy'])->name('document.delete')->middleware('auth');


//End Occupation
//Nominee
Route::get('user-nominee-detalis',[NomineeDetailController::class,'index'])->name('nominee.index')->middleware('auth');
Route::get('/get-all-nominee-users',[NomineeDetailController::class, 'getAllUsers'])->middleware('auth');
Route::post('/add-nominee-detail',[NomineeDetailController::class, 'store'])->middleware('auth');
Route::get('/get-nominee-detail/{id}', [NomineeDetailController::class, 'edit'])->name('nominee.edit')->middleware('auth');
Route::post('/update-nominee-detail/{id}', [NomineeDetailController::class, 'update'])->name('nominee.update')->middleware('auth');
Route::get('/delete-nominee-detail/{id}', [NomineeDetailController::class, 'destroy'])->name('nominee.delete')->middleware('auth');


//End Nominee

Route::get('/', function () {return redirect('sign-in');})->middleware('guest');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('sign-up', [RegisterController::class, 'create'])->middleware('guest')->name('register');
Route::post('sign-up', [RegisterController::class, 'store'])->middleware('guest');
Route::get('sign-in', [SessionsController::class, 'create'])->middleware('guest')->name('login');
Route::post('sign-in', [SessionsController::class, 'store'])->middleware('guest');
Route::post('verify', [SessionsController::class, 'show'])->middleware('guest');
Route::post('reset-password', [SessionsController::class, 'update'])->middleware('guest')->name('password.update');
Route::get('verify', function () {
	return view('sessions.password.verify');
})->middleware('guest')->name('verify'); 
Route::get('/reset-password/{token}', function ($token) {
	return view('sessions.password.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('sign-out', [SessionsController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('profile', [ProfileController::class, 'create'])->middleware('auth')->name('profile');
Route::post('user-profile', [ProfileController::class, 'update'])->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
	Route::get('billing', function () {
		return view('pages.billing');
	})->name('billing');
	Route::get('tables', function () {
		return view('pages.tables');
	})->name('tables');
	Route::get('rtl', function () {
		return view('pages.rtl');
	})->name('rtl');
	Route::get('virtual-reality', function () {
		return view('pages.virtual-reality');
	})->name('virtual-reality');
	Route::get('notifications', function () {
		return view('pages.notifications');
	})->name('notifications');
	Route::get('static-sign-in', function () {
		return view('pages.static-sign-in');
	})->name('static-sign-in');
	Route::get('static-sign-up', function () {
		return view('pages.static-sign-up');
	})->name('static-sign-up');

	Route::get('user-profile', function () {
		return view('pages.laravel-examples.user-profile');
	})->name('user-profile');
});