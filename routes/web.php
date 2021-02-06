<?php

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


Route::get('/', function (){
    return view('auth.login');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware'=>'auth:web'],function () {
    Route::resource('invoices','InvoicesController');
    Route::get('invoice_paid','InvoicesController@getInvoicePaid')->name('invoice.paid');
    Route::get('invoice_unPaid','InvoicesController@getInvoiceUnPaid')->name('invoice.unPaid');
    Route::get('invoice_partial','InvoicesController@getInvoicePartial')->name('invoice.partial');
    Route::get('status/{id}','InvoicesController@statusShow')->name('invoicesStatus.show');
    Route::post('statusUpdate/{id}','InvoicesController@statusUpdate')->name('invoices.statusUpdate');
    Route::get('invoice_print/{id}','InvoicesController@invoice_print')->name('invoice.print');
    Route::get('invoices_export/', 'InvoicesController@export');

    Route::resource('category','CategoryController')->except(['create','show','edit']);
    Route::resource('product','ProductController')->except(['create','show','edit']);
    Route::resource('archive','InvoiceArchiveController');
    Route::get('category/{id}','InvoicesController@getProduct');
    Route::get('invoices_details/{id}','InvoicesDetailsController@show')->name('invoices.details');
    Route::post('delete_file','InvoicesDetailsController@destroy')->name('delete_file');
    Route::get('View_file/{invoice_num}/{file_name}','InvoicesDetailsController@openFile');
    Route::get('download_file/{invoice_num}/{file_name}','InvoicesDetailsController@getFile');
    Route::post('invoice_attachments','InvoicesAttachmentsController@store')->name('invoices.attachments');
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
    Route::get('invoices_reports','InvoicesReportController@getReport')->name('get.report');
    Route::post('search_reports','InvoicesReportController@searchReport')->name('search.report');

    Route::get('customer_reports','CustomerReportController@getCustomerReport')->name('get.customer.report');
    Route::post('search_Customer','CustomerReportController@searchCustomerReport')->name('search.customer.report');

    Route::get('markAsRead','InvoicesController@markAsRead_all')->name('mark');


});

Route::get('/{page}', 'AdminController@index');



