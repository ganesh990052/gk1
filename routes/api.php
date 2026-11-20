<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Api\Address\CurrentAddressController;
use App\Http\Controllers\Api\Document\DocumentController;
use App\Http\Controllers\Api\Chit\ChitController;
use App\Http\Controllers\Api\UserBankDetail\BankDetailController;
use App\Http\Controllers\Api\Occupation\OccupationDetailController;
use App\Http\Controllers\Api\NomineeDetail\NomineeDetailController;
use App\Http\Controllers\Api\Bid\BidController;
use App\Http\Controllers\TestControoler;
use App\Http\Controllers\Api\PinController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\CashfreeController1;
use App\Http\Controllers\VegaahController;
use App\Http\Controllers\Api\User\UserProfileController;
use App\Http\Controllers\Api\EducationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Define routes for Pins
Route::get('/pin-get', [PinController::class, 'index'])->name('pins.index');
Route::post('otp-verify',[PinController::class,'verifyOtpAndUpdatePin']);
Route::post('pin-reset',[PinController::class,'sendOtpEmail']);
Route::get('/pins/create', [PinController::class, 'create'])->name('pins.create');
Route::post('/pins', [PinController::class, 'store'])->name('pins.store');
Route::get('testcron',[TestControoler::class,'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ✅ User Profile Route (auth required)
Route::middleware('auth:sanctum')->get('/user-profile', [UserProfileController::class, 'show']);

// ✅ Education Detail Route (auth required)
Route::middleware('auth:sanctum')->post('/add-education-detail', [EducationController::class, 'store']);

Route::post('/verify-pin', [PinController::class, 'verifyPin']);

//------------user address------------
Route::post('/add-address-detail',[CurrentAddressController::class,'store']);
Route::post('address-detail',[CurrentAddressController::class,'show']);
//------------user end address--------

//------------user document------------
Route::post('/add-document-detail', [DocumentController::class, 'store']);
Route::post('/document-detail', [DocumentController::class, 'show']);

//------------user end document--------
//------------Chit Plan ---------------
Route::get('chit-plan',[ChitController::class,'index']);
Route::post('chit-plan-table',[ChitController::class,'table']);
Route::post('/chit-emi-data', [ChitController::class, 'showEmiData']);
Route::get('/completed-chits', [ChitController::class, 'completedChits']);
//------------End Chit Plan ---------------

//------------User Bank Detalis------------
Route::post('/user-bank-detalis', [BankDetailController::class, 'addBankDetail']);
Route::post('/bank-detalis', [BankDetailController::class, 'show']);

//------------User End Bank Detalis------------
//------------User nominee --------------------
Route::post('/add-nominee-detail',[NomineeDetailController::class, 'store']);
Route::get('/get-nominee-detail/{user_id}', [NomineeDetailController::class, 'getNominee']);
Route::post('nominee-detail',[NomineeDetailController::class,'show']);
//------------User end  nominee --------------------

//------------Bid-----------------------------------
Route::post('bid',[BidController::class,'store']);
//------------End bid-------------------------------
//------------User occupation Detalis------------
Route::post('/add-occupation-detail', [OccupationDetailController::class, 'store']);
Route::post('/occupation-detail', [OccupationDetailController::class, 'show']);

//------------User End occupation Detalis------------


// Route::resource('users', AuthController::class);

// Custom route for create method
Route::post('/register', [AuthController::class, 'create']);
Route::post('/login', [AuthController::class, 'login']);

// ✅ Social Login (Google / Apple)
Route::post('/social-login', [AuthController::class, 'socialLogin']);


Route::post('/admin', [AdminController::class, 'store']);
Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
Route::get('/reset-password1', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// cashfree payment getway start

Route::post('/cashfree/create-order', [CashfreeController1::class, 'createOrder']);
Route::post('/updateEmi', [CashfreeController1::class, 'updateEmi']);
Route::get('/payment/success', [CashfreeController1::class, 'paymentSuccess']);

Route::get('/user-applied-chits', [CashfreeController1::class, 'getUserAppliedChits']);

// ✅ EMI update (Mobile app ke liye direct call)

Route::get('/my-deals', [CashfreeController1::class, 'myDeals']);
Route::post('/apply-chit', [CashfreeController1::class, 'applyChit']);


Route::post('/create-vegaah-payment',[VegaahController::class,'createPayment']);

Route::post('/vegaah/callback',[VegaahController::class,'paymentCallback']);


Route::options('/{any}', function () {
    return response()->json([], 200);
})->where('any', '.*');

// ✅ Firebase Login Route
Route::post('/firebase-login', [App\Http\Controllers\Api\User\SocialAuthController::class, 'firebaseLogin']);