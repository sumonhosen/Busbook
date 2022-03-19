<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\TripController;
use App\Http\Controllers\Web\TripBreakdownController;
use App\Http\Controllers\Web\SeatLayoutController;
use App\Http\Controllers\Web\PlaceController;
use App\Http\Controllers\Web\CounterController;
use App\Http\Controllers\Web\BusController;
use App\Http\Controllers\Web\BusinessController;
use App\Http\Controllers\Web\BannerController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\BetaController;
use App\Http\Controllers\Web\SslCommerzPaymentController;
use App\Http\Controllers\Web\SettingsController;


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

// 1. details page a edit & delete(alert asbe) hobe
// 2. place widget
// 3. token hobe parameter
// 4. Activity Token



############# ROUTE DISMISSAL ##################
// Route::prefix('user')->group(function () {
//   Route::get('/new/view', [UserController::class, 'newUserView']);
//   Route::post('/new/action', [UserController::class, 'newUser']);
//   Route::get('/view/{userToken}', [UserController::class, 'userView']);
//   Route::get('/list', [UserController::class, 'userList']);
//   Route::any('/update/{userToken}', [UserController::class, 'updateUser']);
//   Route::post('/delete/{userToken}', [UserController::class, 'deleteUser']);
//   ***Route::any('/update/password/{userToken}', [UserController::class, 'updatePassword']);***
// });

// Route::group(['middleware' => ['auth']], function () {


// });

Auth::routes();

Route::group(['middleware'=>'auth'], function(){
  Route::prefix('user')->group(function () {
    Route::get('/new/view', [UserController::class, 'newUserView']);
    Route::post('/new/action', [UserController::class, 'newUser']);
    Route::post('/update/{userToken}', [UserController::class, 'updateUser']);
    Route::post('/delete/{userToken}', [UserController::class, 'deleteUser']);

  });

// Route::prefix('business')->group(function () {
//   Route::get('/new/view', [BusinessController::class, 'newBusinessView']);
//   Route::post('/new/action', [BusinessController::class, 'newBusiness']);
//   Route::get('/list', [BusinessController::class, 'businessList']);
//   Route::get('/view/{businessToken}', [BusinessController::class, 'businessView']);
//   Route::any('/update/{businessToken}', [BusinessController::class, 'updateBusiness']);
//   Route::post('/delete/{businessToken}', [BusinessController::class, 'deleteBusiness']);
//   // Route::post('/user/relation/new', [BusinessController::class, 'relateBusinessAndUser']);
//   // ****Route::post('/user/relation/new/user/relation/new/user/relation/new', [BusBusinessController::class, '']);
// });


Route::prefix('business')->group(function () {
  Route::get('/new/view', [BusinessController::class, 'newBusinessView']);
  Route::post('/new/action', [BusinessController::class, 'newBusiness']);
  Route::post('/update/{businessToken}', [BusinessController::class, 'updateBusiness']);
  Route::post('/delete/{businessToken}', [BusinessController::class, 'deleteBusiness']);
});

// Route::prefix('banner')->group(function () {
//   Route::get('/new/view', [BannerController::class, 'newBannerView']);
//   Route::post('/new/action', [BannerController::class, 'newBanner']);

//   Route::get('/view/{bannerToken}', [BannerController::class, 'bannerView']);
//   Route::get('/list', [BannerController::class, 'bannerList']);
//   Route::any('/update/{bannerToken}', [BannerController::class, 'bannerUpdate']);
//   Route::post('/delete/{bannerToken}', [BannerController::class, 'deleteBanner']);
// });

Route::prefix('banner')->group(function () {
  Route::get('/new/view', [BannerController::class, 'newBannerView']);
  Route::post('/new/action', [BannerController::class, 'newBanner']);

  Route::post('/update/{bannerToken}', [BannerController::class, 'bannerUpdate']);
  Route::post('/delete/{bannerToken}', [BannerController::class, 'deleteBanner']);
});

// Route::prefix('bus')->group(function () {
//   Route::get('/new/view', [BusController::class, 'newBusView']);
//   Route::post('/new/action', [BusController::class, 'newBus']);

//   Route::get('/list', [BusController::class, 'busList']);
//   Route::get('/view/{busToken}', [BusController::class, 'busView']);
//   Route::any('/update/{busToken}', [BusController::class, 'updateBus']);
//   Route::post('/delete/{busToken}', [BusController::class, 'deleteBus']);
//   Route::post('/user/relation/new', [BusController::class, 'relateBusAndUser']);
//   Route::post('/user/relation/delete', [BusController::class, '']);
// });


Route::prefix('bus')->group(function () {
  Route::get('/new/view', [BusController::class, 'newBusView']);
  Route::post('/new/action', [BusController::class, 'newBus']);

  Route::post('/update/{busToken}', [BusController::class, 'updateBus']);
  Route::post('/delete/{busToken}', [BusController::class, 'deleteBus']);
  Route::post('/user/relation/new', [BusController::class, 'relateBusAndUser']);
  Route::post('/user/relation/delete', [BusController::class, '']);
});

// Route::prefix('trip')->group(function () {
//   Route::get('/new/view', [TripController::class, 'newTripView']);
//   Route::post('/new/action', [TripController::class, 'newTrip']);
//   Route::get('/list', [TripController::class, 'tripList']);
//   Route::get('/breakdowns/{tripToken}', [TripController::class, 'breakdownList']);
//   Route::get('/breakdown/{tripBreakdownToken}/edit', [TripController::class, 'breakdownEdit']);
//   Route::post('/breakdown/{tripBreakdownToken}/edit/action', [TripController::class, 'breakdownEditAction']);
//   Route::post('/breakdown/{tripToken}/new/action', [TripController::class, 'newBreakdown']);

//   Route::post('/new/{busID}', [TripController::class, '']);
//   Route::get('/view/{tripToken}', [TripController::class, 'tripView']);
//   Route::post('/update/{tripToken}', [TripController::class, 'updateTrip']);
//   Route::post('/delete/{tripToken}', [TripController::class, 'deleteTrip']);
// });


Route::prefix('trip')->group(function () {
  Route::get('/new/view', [TripController::class, 'newTripView']);
  Route::post('/new/action', [TripController::class, 'newTrip']);
  Route::get('/new/bus_list/view', [TripController::class, 'newBusListView']);
  Route::get('/breakdowns/{tripToken}', [TripController::class, 'breakdownList']);
  Route::get('/breakdown/{tripBreakdownToken}/edit', [TripController::class, 'breakdownEdit']);
  Route::post('/breakdown/{tripBreakdownToken}/edit/action', [TripController::class, 'breakdownEditAction']);
  Route::post('/breakdown/{tripToken}/new/action', [TripController::class, 'newBreakdown']);


  Route::post('/new/{busID}', [TripController::class, '']);
  Route::get('/view/{tripToken}', [TripController::class, 'tripView']);
  Route::post('/update/{tripToken}', [TripController::class, 'updateTrip']);
  Route::post('/delete/{tripToken}', [TripController::class, 'deleteTrip']);
});

// Route::prefix('place')->group(function () {
//   Route::get('/new/view', [PlaceController::class, 'newPlaceView']);
//   Route::post('/new/action', [PlaceController::class, 'newPlace']);
//   Route::get('/list', [PlaceController::class, 'placeList']);

//   Route::get('/view/{id}', [PlaceController::class, 'placeView']);
//   Route::post('/update/{id}', [PlaceController::class, 'updatePlace']);
//   Route::post('/delete/{id}', [PlaceController::class, 'deletePlace']);
// });

Route::prefix('place')->group(function () {
  Route::get('/new/view', [PlaceController::class, 'newPlaceView']);
  Route::post('/new/action', [PlaceController::class, 'newPlace']);

  Route::post('/update/{placeToken}', [PlaceController::class, 'updatePlace']);
  Route::post('/delete/{placeToken}', [PlaceController::class, 'deletePlace']);
});

// Route::prefix('counter')->group(function () {
//   Route::get('/new/view', [CounterController::class, 'newCounterView']);
//   Route::post('/new/action', [CounterController::class, 'newCounter']);
//   Route::get('/list', [CounterController::class, 'counterList']);
//   Route::get('/view/{counterToken}', [CounterController::class, 'counterView']);

//   Route::post('/update/{counterToken}', [CounterController::class, 'updateCounter']);
//   Route::post('/delete/{counterToken}', [CounterController::class, 'deleteCounter']);
//   Route::post('/user/relation/new', [CounterController::class, 'relateCounterAndUser']);
//   Route::post('/user/relation/delete', [CounterController::class, '']);
// });

Route::prefix('counter')->group(function () {
  Route::get('/new/view', [CounterController::class, 'newCounterView']);
  Route::post('/new/action', [CounterController::class, 'newCounter']);

  Route::post('/update/{counterToken}', [CounterController::class, 'updateCounter']);
  Route::post('/delete/{counterToken}', [CounterController::class, 'deleteCounter']);

  Route::get('/user/relation/new/view/{counterToken}', [CounterController::class, 'relateCounterAndUserView']);
  Route::post('/user/relation/new/action', [CounterController::class, 'relateCounterAndUser']);
  Route::post('/user/relation/delete/{token}', [CounterController::class, 'deleteCounterAndUser']);

  Route::get('/trip/relation/new/view/{counterToken}', [CounterController::class, 'relateCounterAndTripView']);
  Route::post('/trip/relation/new/action', [CounterController::class, 'relateCounterAndTrip']);
  Route::post('/trip/relation/delete/{token}', [CounterController::class, 'deleteCounterAndTrip']);
});

Route::prefix('tripBreakdown')->group(function () {
  Route::post('/new', [TripBreakdownController::class, 'newTripBreakdown']);
  Route::post('/new/{tripID}', [TripBreakdownController::class, '']);
  Route::get('/view/{id}', [TripBreakdownController::class, 'updateTripBreakdown']);
  Route::post('/update/{id}', [TripBreakdownController::class, '']);
  Route::post('/delete/{id}', [TripBreakdownController::class, '']);
});

Route::prefix('seatLayout')->group(function () {
  Route::post('/new', [SeatLayoutController::class, 'newSeatLayout']);
  Route::get('/view/{id}', [SeatLayoutController::class, '']);
  Route::post('/update/{id}', [SeatLayoutController::class, 'updateSeatLayout']);
  Route::post('/delete/{id}', [SeatLayoutController::class, '']);
});

Route::get('/pages', function(){
  return view('pages/user/new');
});

Route::get('/dashboard', function(){
  return view('pages.dashboard.dashboard');
});


Route::prefix('role')->group(function () {
  Route::get('/new/view', [RoleController::class, 'newRoleView']);
  Route::post('/new/action', [RoleController::class, 'newRole']);
  Route::get('/view/{roleToken}', [RoleController::class, 'singleRoleView']);
  Route::post('/single/view/action', [RoleController::class, 'singleRoleAction']);
   Route::get('/privacy/policy', [RoleController::class, 'newPrivacyPolicyView']);
   Route::post('/privacy/policy/action', [RoleController::class, 'newPrivacyPolicyAction']);
   Route::get('/privacy/policy/content', [RoleController::class, 'newPrivacyPolicyContentView']);
   Route::post('/privacy/policy/content/action', [RoleController::class, 'newPrivacyPolicyContentAction']);
  
});


Route::prefix('beta')->group(function () {
  Route::get('/dashboard', [BetaController::class, 'dashboardView'])->name('dashboard');
  Route::get('/login', [BetaController::class, 'loginView']);
  Route::post('/ajax/login/action', [BetaController::class, 'ajaxLoginAction']);
  Route::post('/ajax/device/token', [BetaController::class, 'ajaxDeviceToken']);

  Route::get('/trips', [BetaController::class, 'tripsView']);
  Route::get('/ajax/trips', [BetaController::class, 'ajaxTrips']);
  Route::get('/ajax/places/list', [BetaController::class, 'ajaxPlacesList']);
  Route::get('/ajax/trip/list', [BetaController::class, 'ajaxTripsList']);
  Route::post('/ajax/trip/booking/action', [BetaController::class, 'ajaxTripBookingAction']);
  Route::get('/ajax/seat_view', [BetaController::class, 'seatView']);
  Route::post('/ajax/show_seat', [BetaController::class, 'showSeat']);
  Route::post('ajax/booking_seat',[BetaController::class, 'bookingSeat']);

  Route::post('ajax/show_seat/history',[BetaController::class,'showHistory']);

  Route::get('/pass/{pass}', [BetaController::class, 'demoPassword']);

//ssl commerz

// SSLCOMMERZ Start
Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);

Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);


});

  Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
  Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
  Route::get('/privacy-policy', [App\Http\Controllers\HomeController::class, 'privacy_policy'])->name('home');
  Route::get('sms_settings',[SettingsController::class,'smsSetting'])->name('sms_setting');
  Route::get('counter_sms',[SettingsController::class,'counter_sms']);
  Route::get('app_sms',[SettingsController::class,'app_sms']);
  Route::post('is_admin_sms',[SettingsController::class,'is_admin_sms']);
  Route::get('is_admin_counter',[SettingsController::class,'is_admin_counter']);
  Route::get('is_admin_app',[SettingsController::class,'is_admin_app']);
});


