<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserManagement;
use App\Http\Controllers\API\SystemManagement;
use App\Http\Controllers\API\PlaceManagement;
use App\Http\Controllers\API\TripManagement;
use App\Http\Controllers\API\BookingManagement;
use App\Http\Controllers\API\BetaController;
use App\Http\Controllers\API\AppUserController;
use App\Http\Controllers\API\SslCommerzPaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('app_user')->group(function () {
    Route::post('/send_otp', [AppUserController::class, 'sendOTP']);
    Route::post('/check_otp', [AppUserController::class, 'checkOTP']);
    Route::get('/place/list', [PlaceManagement::class, 'placeList']);
    Route::post('/trip/list', [AppUserController::class, 'TripList']);

    Route::post('/trip/booking/action', [AppUserController::class, 'TripBookingAction']);

    Route::post('/seat_view', [AppUserController::class, 'seatView']);
    Route::post('/show_seat', [AppUserController::class, 'showSeat']);


    Route::post('/show_history',[AppUserController::class,'showHistory']);
    Route::post('/history_details',[AppUserController::class,'historyDetails']);

	Route::post('/hold_seat', [AppUserController::class, 'holdSeat']);
	Route::post('/delete_hold', [AppUserController::class, 'delete_hold']);
  
    Route::get('privacy/policy',[AppUserController::class,'privacyPolicy']);
    Route::post('privacy/policy/content',[AppUserController::class,'privacyPolicyContent']);

    Route::post('/pay', [SslCommerzPaymentController::class, 'index']);

    Route::post('/success', [SslCommerzPaymentController::class, 'success']);
    Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
    Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

    Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);

});

Route::prefix('public')->group(function () {
    Route::post('/system/new/deviceToken', [SystemManagement::class, 'newDeviceToken']);
    Route::post('/user/special/login', [UserManagement::class, 'specialLogin']);
});



Route::group(['middleware' => ['APIAuth']], function () {

	Route::get('/user/details/{userToken}', [UserManagement::class, 'userDetails']);
	Route::post('/user/update', [UserManagement::class, 'updateUser']);
	Route::get('/place/list', [PlaceManagement::class, 'placeList']);
	//Route::post('/search/trips', [TripManagement::class, 'searchTrips']);
	//Route::post('/booking/initiate', [BookingManagement::class, 'initiateBooking']);
	//Route::get('/booking/loadLayout/{bookingToken}', [BookingManagement::class, 'loadLayout']);
	//Route::post('/booking/select/seat', [BookingManagement::class, 'selectSeat']);
	//Route::get('/booking/details/{bookingToken}', [BookingManagement::class, 'bookingDetails']);
	//Route::post('/booking/confirm/counter', [BookingManagement::class, 'confirmBookingCounter']);
	//Route::post('/booking/confirm/user', [BookingManagement::class, 'confirmBookingUser']);

	Route::post('/ajax/hold_seat', [BookingManagement::class, 'holdSeat']);
	Route::post('/ajax/delete_hold', [BookingManagement::class, 'delete_hold']);
	Route::get('/history/booking/list/{mode}', [BookingManagement::class, 'historyBookingList']);


    //beta

    Route::post('ajax/login/action', [BetaController::class, 'ajaxLoginAction']);
    Route::post('ajax/device/token', [BetaController::class, 'ajaxDeviceToken']);

    Route::post('search-trips', [BetaController::class, 'tripsView']);
    Route::get('ajax/trips', [BetaController::class, 'ajaxTrips']);
    Route::get('ajax/places/list', [BetaController::class, 'ajaxPlacesList']);
    Route::post('/ajax/trip/booking/action', [BetaController::class, 'ajaxTripBookingAction']);

    Route::post('ajax/trip/list', [BetaController::class, 'ajaxTripsList']);
    Route::post('ajax/trip/app_user', [BetaController::class, 'ajaxTripBookingAction']);
    Route::post('ajax/seat_view', [BetaController::class, 'seatView']);
    Route::post('ajax/show_seat', [BetaController::class, 'showSeat']);

    Route::post('ajax/submit',[BetaController::class, 'bookingSeat']);
    Route::post('ajax/show_history',[BetaController::class,'showHistory']);
  
    Route::post('ajax/history_details',[BetaController::class,'historyDetails']);
  
    Route::post('ajax/seat_details',[BetaController::class,'seatDetails']);
    Route::post('ajax/see_seat',[BetaController::class,'seeSeat']);
  
  
    Route::get('/pass/{pass}', [BetaController::class, 'demoPassword']);
	Route::get('cache-clear',      [BetaController::class, 'cacheClear']);



});

