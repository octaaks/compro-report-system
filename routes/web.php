<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Helpers\LocaleEnum;
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

Route::get('/', function () {
    $company_id = 61373;
    return redirect(url('/', [$company_id]));
});

Route::get('/{company_id}', function ($company_id) {
    return view('dashboard', compact('company_id'));
});

Route::get('outofstock/{company_id}', function ($company_id) {
    return view('outofstock', compact('company_id'));
});

Route::get('storeinfo/{company_id}', function ($company_id) {
    return view('storeinfo', compact('company_id'));
});

Route::get('requestedFile/{company_id}', function ($company_id) {
    return view('requestedFile', compact('company_id'));
});

Route::get('change-language/{locale}', function ($locale) {
    request()->session()->put('locale', $locale);
    return redirect()->back();
})->name('change-language');
 
Route::get('lang/{language}', 'LocalizationController@switch')->name('localization.switch');
 
// Route::get('/transaction/{company_id}', 'TransactionsController@transaction');
// Route::get('/transactionExport/{company_id}', 'TransactionsController@transactionExport');
