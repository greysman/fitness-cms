<?php

use App\Http\Controllers\Payment\YooKassaController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SiteController;
use App\Models\Misc\SmscApi;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [SiteController::class, 'index'])->name('home');
Route::get('/faq', [SiteController::class, 'faq'])->name('faq');
Route::post('/income-request', [SiteController::class, 'income-request'])->name('income-request');
Route::post('/review', [ReviewController::class, 'store'])->name('store-review');

Route::controller(YooKassaController::class)->group(function () {
    Route::get('/payment/{id]', 'payment')->name('yookassa-payment');
    Route::post('/webhook', 'webhook')->name('yookassa-webhook');
    Route::get('/payment/success', 'success')->name('yookassa-payment-success');
    Route::get('/payment/failed', 'failed')->name('yookassa-payment-failed');
});

Route::get('/test', function () {
    $smsApi = new SmscApi();
    // dd($smsApi->get_balance());
    dd($smsApi->send_sms('79604566663', 'Тестовое сообщение'));
});

Route::get('{page}/{subs?}', ['uses' => '\App\Http\Controllers\PageController@index'])
    ->where(['page' => '^(((?=(?!admin))(?=(?!\/)).))*$', 'subs' => '.*']);
