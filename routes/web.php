<?php

use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\RootController;
use App\Http\Controllers\Installer\InstallerController;
use App\Http\PaymentGateways\Gateways\Paytm;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampleController;

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


Route::prefix('install')->name('installer.')->middleware(['web'])->group(function () {
    Route::get('/', [InstallerController::class, 'index'])->name('index');
    Route::get('/requirement', [InstallerController::class, 'requirement'])->name('requirement');
    Route::get('/permission', [InstallerController::class, 'permission'])->name('permission');
    Route::get('/license', [InstallerController::class, 'license'])->name('license');
    Route::post('/license', [InstallerController::class, 'licenseStore'])->name('licenseStore');
    Route::get('/site', [InstallerController::class, 'site'])->name('site');
    Route::post('/site', [InstallerController::class, 'siteStore'])->name('siteStore');
    Route::get('/database', [InstallerController::class, 'database'])->name('database');
    Route::post('/database', [InstallerController::class, 'databaseStore'])->name('databaseStore');
    Route::get('/final', [InstallerController::class, 'final'])->name('final');
    Route::get('/final-store', [InstallerController::class, 'finalStore'])->name('finalStore');
});


Route::get('/', [RootController::class, 'index'])->middleware(['installed'])->name('home');
Route::prefix('payment')->name('payment.')->middleware(['installed'])->group(function () {
    Route::get('/{order}/pay', [PaymentController::class, 'index'])->name('index');
    Route::get('/{order}/pay/mobile-money', [PaymentController::class, 'mobileMoneyIndex'])->name('momo');
    Route::get('/{order}/pay/cod', [PaymentController::class, 'codIndex'])->name('cod');
    Route::post('/{order}/pay/momo', [PaymentController::class, 'payMomo'])->name('pay-momo');
    Route::post('/{order}/pay/cod', [PaymentController::class, 'payCod'])->name('pay-cod');
    Route::post('/{order}/pay', [PaymentController::class, 'payment'])->name('store');
    Route::match(['get', 'post'], '/{order}/success', [PaymentController::class, 'success'])->name('success');
    Route::match(['get', 'post'], '/{order}/fail', [PaymentController::class, 'fail'])->name('fail');
    Route::match(['get', 'post'], '/{order}/cancel', [PaymentController::class, 'cancel'])->name('cancel');
    Route::get('/successful/{order}', [PaymentController::class, 'successful'])->name('successful');
});

Route::get('/about', [RootController::class, 'index'])->name('about');
Route::get('/contact', [RootController::class, 'index'])->name('contact');
Route::get('/push', [SampleController::class, 'index'])->name('push');
