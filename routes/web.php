<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Str;
use App\Http\Controllers\SmartSuggestionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\VoiceAssistantController;

Route::get('/', function(){
        return view('welcome');
    });

Route::middleware(['web'])->group(function () {

   Route::get('/home', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        Auth::logout();
        return redirect('/login')->withErrors(['login' => 'Admins are not allowed here.']);
    }

    return view('home');
});
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'postLogin'])->name('login.post');

    Route::get('registration', [AuthController::class, 'registration'])->name('registration');
    Route::post('registration', [AuthController::class, 'postRegistration'])->name('registration.post');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/all-meals', [MealController::class, 'allMealsWithRestaurants'])->name('all-meals');
    Route::get('/meals/{slug}/restaurants', [MealController::class, 'showRestaurants'])->name('meals.restaurants');
    Route::get('/restaurant/{id}', [RestaurantController::class, 'show'])->name('restaurant.show');

    Route::get('search', [SearchController::class, 'showSearchPage'])->name('search');
    Route::post('/set-city', [SearchController::class, 'storeCity'])->name('search.set.city');
    Route::get('/clear-city', [SearchController::class, 'clearCity'])->name('search.clear.city');
    Route::get('/search-meals', [SearchController::class, 'searchMeals'])->name('search.meals');


    Route::get('/category/{slug}', [CategoryController::class, 'showCategory'])->name('category.main'); 
    Route::get('/category/redirect/{slug}', [CategoryController::class, 'redirectToFirstMealInCategory'])->name('category.redirect');

    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

    Route::get('/cart', [CartController::class, 'index'])->name('cart.show');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/fragment', [CartController::class, 'cartFragment'])->name('cart.fragment');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/cart/cancel', [CartController::class, 'cancel'])->name('cart.cancel');
    Route::post('/cart/restore', [CartController::class, 'restore'])->name('cart.restore');


    Route::get('/order', [OrderController::class, 'show'])->name('place.order');
    Route::post('/place-order', [OrderController::class, 'submit'])->name('order.submit');
    Route::post('/apply-coupon', [OrderController::class, 'applyCoupon'])->name('coupon.apply');
    Route::get('/order/track', [OrderController::class, 'confirmMultipleDeliveries'])->name('order.track');

    Route::get('/', [SmartSuggestionController::class, 'index'])->name('home');

    Route::post('/cart/cancel', [CartController::class, 'cancel'])->name('cart.cancel');
    Route::post('/refund', [OrderController::class, 'refund'])->name('order.refund');


});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/home')->with('not_admin', 'Access Denied: Only admins can access this panel.');
        }

        return app(DashboardController::class)->index();
    })->name('dashboard');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/ready', [AdminOrderController::class, 'markReady'])->name('orders.markReady');

});

Route::get('/start-voice-assistant', [VoiceAssistantController::class, 'startCall']);
Route::post('/dialogflow/webhook', [VoiceAssistantController::class, 'handleDialogflow']);
Route::post('/twilio/voice', [VoiceAssistantController::class, 'twilioWebhook']);