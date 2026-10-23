<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PaymobReturnController;
use App\Http\Controllers\PaymobWebhookController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TrackOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/locale/{locale}', LocaleController::class)->name('locale.switch');

Route::get('/shop', [CatalogController::class, 'shop'])->name('shop');
Route::get('/categories/{category}', [CatalogController::class, 'category'])->name('categories.show');
Route::get('/products/{product}', [CatalogController::class, 'product'])->name('products.show');
Route::get('/track', TrackOrderController::class)->name('orders.track');
Route::get('/bundles', [CatalogController::class, 'bundles'])->name('bundles.index');
Route::get('/bundles/{bundle}', [CatalogController::class, 'bundle'])->name('bundles.show');

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
    Route::get('/cart/summary', [CartController::class, 'summary'])->name('cart.summary');
    Route::post('/cart/items', [CartController::class, 'store'])->name('cart.items.store');
    Route::patch('/cart/items/{key}', [CartController::class, 'update'])->name('cart.items.update');
    Route::delete('/cart/items/{key}', [CartController::class, 'destroy'])->name('cart.items.destroy');
    Route::post('/cart/coupon', [CartController::class, 'coupon'])->name('cart.coupon');
    Route::delete('/cart/coupon', [CartController::class, 'forgetCoupon'])->name('cart.coupon.destroy');
});

Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:10,1')->name('checkout.store');
Route::get('/checkout/thanks', [CheckoutController::class, 'thanks'])->name('checkout.thanks');
Route::get('/checkout/paymob/return', PaymobReturnController::class)->name('checkout.paymob.return');
Route::get('/checkout/paymob/status', [PaymobReturnController::class, 'status'])->name('checkout.paymob.status');
Route::post('/checkout/paymob/retry/{order}', [CheckoutController::class, 'retry'])->name('checkout.paymob.retry');
Route::post('/payments/paymob/webhook', PaymobWebhookController::class)->name('payments.paymob.webhook');

Route::match(['get', 'post'], '/invoices/{order}', InvoiceController::class)->middleware('throttle:20,1')->name('invoices.download');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendReset'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');
    Route::post('/otp/send', [AuthController::class, 'sendOtp'])->middleware('throttle:5,1')->name('otp.send');
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->middleware('throttle:10,1')->name('otp.verify');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::post('/bundles/{bundle}/subscribe', [SubscriptionController::class, 'store'])->middleware('auth')->name('bundles.subscribe');
Route::redirect('/account', '/my')->name('account.home');
