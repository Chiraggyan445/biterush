<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    MealController,
    RestaurantController,
    SearchController,
    CategoryController,
    GoogleController,
    CartController,
    OrderController,
    SmartSuggestionController
};

use App\Http\Controllers\Admin\{
    AdminController,
    DashboardController,
    OrderController as AdminOrderController,
    RestaurantController as AdminRestaurantController,
    JsonMealController as AdminJsonMealController,
    SettingsController
};

// 🌐 Public route
Route::get('/', fn() => view('welcome'));

// 🧑‍💻 Authentication Routes (for Customers)
Route::middleware(['web'])->group(function () {
    // Auth
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'postLogin'])->name('login.post');
    Route::get('/registration', [AuthController::class, 'registration'])->name('registration');
    Route::post('/registration', [AuthController::class, 'postRegistration'])->name('registration.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Google OAuth
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
});

// 🏠 Customer Dashboard & Meals
Route::middleware(['web'])->group(function () {
    Route::get('/home', [SmartSuggestionController::class, 'index'])->name('home');
    Route::get('/all-meals', [MealController::class, 'allMealsWithRestaurants'])->name('all-meals');
    Route::get('/meals/{slug}/restaurants', [MealController::class, 'showRestaurants'])->name('meals.restaurants');
    Route::get('/restaurant/{id}', [RestaurantController::class, 'show'])->name('restaurant.show');

    // Categories
    Route::get('/category/{slug}', [CategoryController::class, 'showCategory'])->name('category.main');
    Route::get('/category/redirect/{slug}', [CategoryController::class, 'redirectToFirstMealInCategory'])->name('category.redirect');

    // Search
    Route::get('/search', [SearchController::class, 'showSearchPage'])->name('search');
    Route::post('/set-city', [SearchController::class, 'storeCity'])->name('search.set.city');
    Route::get('/clear-city', [SearchController::class, 'clearCity'])->name('search.clear.city');
    Route::get('/search-meals', [SearchController::class, 'searchMeals'])->name('search.meals');
});

// 🛒 Cart & Checkout
Route::middleware(['web'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.show');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/fragment', [CartController::class, 'cartFragment'])->name('cart.fragment');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/cart/cancel', [CartController::class, 'cancel'])->name('cart.cancel');
    Route::post('/cart/restore', [CartController::class, 'restore'])->name('cart.restore');
});

// 📦 Orders & Payments
Route::middleware(['web'])->group(function () {
    Route::get('/order', [OrderController::class, 'show'])->name('place.order');
    Route::post('/place-order', [OrderController::class, 'submit'])->name('order.submit');
    Route::post('/apply-coupon', [OrderController::class, 'applyCoupon'])->name('coupon.apply');
    Route::get('/order/track/{id}', [OrderController::class, 'confirmMultipleDeliveries'])->name('order.track');
    Route::get('/order/scheduled/{id}', [OrderController::class, 'scheduled'])->name('order.schedule');
    Route::post('/refund', [OrderController::class, 'refund'])->name('order.refund');
    Route::get('/order/check-status/{id}', [OrderController::class, 'checkStatus']);
});

// 🔐 Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Admin Guest Routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminController::class, 'login'])->name('login.submit');
    });

    // Admin Authenticated Routes
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/ready', [AdminOrderController::class, 'markReady'])->name('orders.markReady');

        // Restaurants
        Route::get('/restaurants', [AdminRestaurantController::class, 'index'])->name('restaurants.index');
        Route::post('/restaurants', [AdminRestaurantController::class, 'store'])->name('restaurants.store');
        Route::put('/restaurants/{id}', [AdminRestaurantController::class, 'update'])->name('restaurants.update');
        Route::delete('/restaurants/{id}', [AdminRestaurantController::class, 'destroy'])->name('restaurants.destroy');

        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::get('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');

        Route::get('/meals', [AdminJsonMealController::class, 'index'])->name('meals.index');
        Route::post('/meals', [AdminJsonMealController::class, 'store'])->name('meals.store');
        Route::post('/meals/{slug}/update', [AdminJsonMealController::class, 'update'])->name('meals.update');
        Route::delete('/meals/{slug}', [AdminJsonMealController::class, 'destroy'])->name('meals.destroy');

        Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->except(['create', 'show', 'edit']);

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});
