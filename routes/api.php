<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route: get
   Route::name('dashboardGraph')->get('dashboardGraph/{company_id}', 'DashboardController@dashboardGraph');
   Route::name('dashboardActivity')->get('dashboardActivity/{company_id}', 'DashboardController@dashboardActivity');
      
//storeinfo graph

   Route::get('realtime/{company_id}', 'TransactionsController@graphRealtime');
   Route::get('lastweek/{company_id}', 'TransactionsController@graphLastweek');
   Route::get('lastmonth/{company_id}', 'TransactionsController@graphLastmonth');

   Route::get('day/{company_id}', 'TransactionsController@graphDay');
   Route::get('week/{company_id}', 'TransactionsController@graphWeek');
   Route::get('month/{company_id}', 'TransactionsController@graphMonth');
   Route::get('year/{company_id}', 'TransactionsController@graphYear');
   

   //storeinfo graph

    Route::get('transaction/{company_id}', 'TransactionsController@transaction');
    Route::name('transactionGraph')->get('transactionGraph/{company_id}', 'TransactionsController@transactionGraph');
    Route::name('transactionExport')->get('transactionExport/{company_id}', 'TransactionsController@transactionExport');
    Route::name('countTransaction')->get('countTransaction/{company_id}', 'TransactionsController@countTransaction');

   //Activity
   Route::name('activity')->get('activity/{company_id}', 'ActivitiesController@activity');
   Route::name('activity_create')->get('activity_create', 'ActivitiesController@activity_create');
   Route::name('activity_update')->get('activity_update/{id}', 'ActivitiesController@activity_update');

   //transaction
   Route::name('transaction')->get('transaction/{company_id}', 'TransactionsController@transaction');
   Route::name('transactionGraph')->get('transactionGraph/{company_id}', 'TransactionsController@transactionGraph');
   Route::name('transactionExport')->get('transactionExport/{company_id}', 'TransactionsController@transactionExport');
   Route::name('countTransaction')->get('countTransaction/{company_id}', 'TransactionsController@countTransaction');

   //product (out of stock)
   Route::name('product')->get('product/{company_id}', 'ProductController@product');

   //Storage Usage
   Route::name('getStorageUsage')->get('getStorageUsage/{company_id}', 'StorageUsageController@getStorageUsage');

   //product_log (out of stock)
   Route::name('product_log')->get('product_log/{company_id}', 'ProductLogController@product_log');

   //cities
   Route::name('citiesTransaction')->get('citiesTransaction/{company_id}', 'CityController@citiesTransaction');

   //requested files
   Route::name('requestedFile')->get('requestedFile/{company_id}', 'RequestedFileController@file');
   Route::name('download_requestedFile')->get('download_requestedFile/{company_id}', 'RequestedFileController@downloadFile');

   Route::name('citiesExport')->get('citiesExport/{company_id}', 'CityController@citiesExport');
   Route::name('citiesDownload')->get('citiesDownload/{company_id}', 'CityController@downloadCitiesFile');
   
//Route: post
   //dashboard graph
   Route::name('dashboardGraph')->post('dashboardGraph/{company_id}', 'DashboardController@dashboardGraph');

   //transaction
   Route::name('transaction')->post('transaction/{company_id}', 'TransactionsController@transaction');
   Route::name('transactionGraph')->post('transactionGraph/{company_id}', 'TransactionsController@transactionGraph');
   //  Route::name('editStatus')->post('editStatus/{company_id}', 'TransactionsController@editStatus');
   Route::name('transactionExport')->post('transactionExport/{company_id}', 'TransactionsController@transactionExport');
   Route::name('daily_transaction_create')->post('daily_transaction_create', 'TransactionsController@daily_transaction_create');
   Route::name('weekly_transaction_create')->post('weekly_transaction_create', 'TransactionsController@weekly_transaction_create');
   Route::name('monthly_transaction_create')->post('monthly_transaction_create', 'TransactionsController@monthly_transaction_create');
   Route::name('yearly_transaction_create')->post('yearly_transaction_create', 'TransactionsController@yearly_transaction_create');
   
   //activity
   Route::name('activity')->post('activity/{company_id}', 'ActivitiesController@activity');
   Route::name('activity_create')->post('activity_create', 'ActivitiesController@activity_create');
   Route::name('activity_update')->post('activity_update/{id}', 'ActivitiesController@activity_update');

   //product (out of stock)
   Route::name('product')->post('product/{company_id}', 'ProductController@product');
   Route::name('product_create')->get('product_create', 'ProductController@product_create');
   Route::name('product_create')->post('product_create', 'ProductController@product_create');
   Route::name('product_update')->post('product_update/{company_id}/{id}', 'ProductController@product_update');
   
   //product_log (out of stock)
   Route::name('product_log')->post('product_log/{company_id}', 'ProductLogController@product');
   Route::name('product_log_create')->post('product_log_create', 'ProductLogController@product_log_create');
   Route::name('product_log_update')->post('product_log_update/{id}', 'ProductLogController@product_log_update');
   
   //Storage Usage
   Route::name('storageUsage_create')->post('storageUsage_create', 'StorageUsageController@storageUsage_create');
   Route::name('storageUsage_update')->post('storageUsage_update', 'StorageUsageController@storageUsage_update');

   //cities
   Route::name('citiesTransaction')->post('citiesTransaction/{company_id}', 'CityController@citiesTransaction');
   Route::name('citiesDownload')->post('citiesDownload/{company_id}', 'CityController@downloadCitiesFile');

   //requested file
   Route::name('requestedFile')->post('requestedFile/{company_id}', 'RequestedFileController@file');
   Route::name('download_requestedFile')->post('download_requestedFile/{company_id}', 'RequestedFileController@downloadFile');

   //report daily user registered
   Route::name('daily_user_register_create')->post('daily_user_register_create', 'UserRegisterController@daily_user_register_create');
   Route::name('monthly_user_register_create')->post('monthly_user_register_create', 'UserRegisterController@monthly_user_register_create');
   Route::name('user_register_log_create')->post('user_register_log_create', 'UserRegisterController@user_register_log_create');

   Route::name('daily_user_register_update')->post('daily_user_register_update/{id}', 'UserRegisterController@daily_user_register_update');
   Route::name('user_register_log_update')->post('user_register_log_update/{id}', 'UserRegisterController@user_register_log_update');

   //report daily send email
   Route::name('daily_send_email_create')->post('daily_send_email_create', 'EmailSendController@daily_send_email_create');
   Route::name('monthly_send_email_create')->post('monthly_send_email_create', 'EmailSendController@monthly_send_email_create');
   Route::name('send_email_log_create')->post('send_email_log_create', 'EmailSendController@send_email_log_create');

   Route::name('daily_send_email_update')->post('daily_send_email_update/{id}', 'EmailSendController@daily_send_email_update');
   Route::name('send_email_log_update')->post('send_email_log_update/{id}', 'EmailSendController@send_email_log_update');

   //report daily push Notification
   Route::name('daily_push_notification_create')->post('daily_push_notification_create', 'PushNotificationController@daily_push_notification_create');
   Route::name('monthly_push_notification_create')->post('monthly_push_notification_create', 'PushNotificationController@monthly_push_notification_create');
   Route::name('push_notification_log_create')->post('push_notification_log_create', 'PushNotificationController@push_notification_log_create');

   Route::name('daily_push_notification_update')->post('daily_push_notification_update/{id}', 'PushNotificationController@daily_push_notification_update');
   Route::name('push_notification_log_update')->post('push_notification_log_update/{id}', 'PushNotificationController@push_notification_log_update');
   
//Route: delete
   //transaction
   Route::name('deleteTransaction')->delete('deleteTransaction/{company_id}', 'TransactionsController@deleteTransaction');

   //product (out of stock)
   Route::name('product_delete')->delete('product_delete/{company_id}/{id}', 'ProductController@product_delete');

   //product_log (out of stock)
   Route::name('product_log_delete')->delete('product_log_delete/{id}', 'ProductLogController@product_log_delete');

   //Storage Usage
   Route::name('storageUsage_delete')->delete('storageUsage_delete/{id}', 'StorageUsageController@storageUsage_delete');

   //requested file
   Route::name('delete_requestedFile')->delete('delete_requestedFile/{company_id}', 'RequestedFileController@deleteFile');

   //activity
   Route::name('activity_delete')->delete('activity_delete/{id}', 'ActivitiesController@activity_delete');

   //report daily user registered
   Route::name('daily_user_register_delete')->delete('daily_user_register_delete/{id}', 'UserRegisterController@daily_user_register_delete');
   Route::name('user_register_log_delete')->delete('user_register_log_delete/{id}', 'UserRegisterController@user_register_log_delete');

   //report daily send email
   Route::name('daily_send_email_delete')->delete('daily_send_email_delete/{id}', 'EmailSendController@daily_send_email_delete');
   Route::name('send_email_log_delete')->delete('send_email_log_delete/{id}', 'EmailSendController@send_email_log_delete');

   //report daily push Notification
   Route::name('daily_push_notification_delete')->delete('daily_push_notification_delete/{id}', 'PushNotificationController@daily_push_notification_delete');
   Route::name('push_notification_log_delete')->delete('push_notification_log_delete/{id}', 'PushNotificationController@push_notification_log_delete');
