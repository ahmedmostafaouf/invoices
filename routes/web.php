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
/////////////////////////////// start Invoices Route//////////////////////////////////////////////////////////
Route::group(['middleware'=>'auth:web','prefix'=>'Invoices'],function () {
            Route::resource('invoices','InvoicesController');
            Route::post('delete_all','InvoicesController@deleteAllChecked')->name('delete.all');
            Route::get('invoice_paid','InvoicesController@getInvoicePaid')->name('invoice.paid');
            Route::get('invoice_unPaid','InvoicesController@getInvoiceUnPaid')->name('invoice.unPaid');
            Route::get('invoice_partial','InvoicesController@getInvoicePartial')->name('invoice.partial');
            Route::get('status/{id}','InvoicesController@statusShow')->name('invoicesStatus.show');
            Route::post('statusUpdate/{id}','InvoicesController@statusUpdate')->name('invoices.statusUpdate');
            Route::get('invoice_print/{id}','InvoicesController@invoice_print')->name('invoice.print');
            Route::get('invoices_export/', 'InvoicesController@export');
            Route::get('category/{id}','InvoicesController@getProduct');
            Route::get('markAsRead','InvoicesController@markAsRead_all')->name('mark');
    /////////////////////////////// end Invoices Route//////////////////////////////////////////////////////////

/////////////////////////////// start category Route//////////////////////////////////////////////////////////
            Route::resource('category','CategoryController')->except(['create','show','edit']);
    /////////////////////////////// end Category Route//////////////////////////////////////////////////////////
/////////////////////////////// start produoct Route//////////////////////////////////////////////////////////
            Route::resource('product','ProductController')->except(['create','show','edit']);
    /////////////////////////////// end produoct Route//////////////////////////////////////////////////////////

    /////////////////////////////// start archive Route//////////////////////////////////////////////////////////
            Route::resource('archive','InvoiceArchiveController');
    /////////////////////////////// start archive Route//////////////////////////////////////////////////////////

    /////////////////////////////// start invoices_details Route//////////////////////////////////////////////////////////
            Route::get('invoices_details/{id}','InvoicesDetailsController@show')->name('invoices.details');
            Route::post('delete_file','InvoicesDetailsController@destroy')->name('delete_file');
            Route::get('View_file/{invoice_num}/{file_name}','InvoicesDetailsController@openFile');
            Route::get('download_file/{invoice_num}/{file_name}','InvoicesDetailsController@getFile');
    /////////////////////////////// end invoices_details Route//////////////////////////////////////////////////////////

    /////////////////////////////// start invoice_attachments Route//////////////////////////////////////////////////////////
            Route::post('invoice_attachments','InvoicesAttachmentsController@store')->name('invoices.attachments');
    /////////////////////////////// end invoice_attachments Route//////////////////////////////////////////////////////////

    /////////////////////////////// start roles Route//////////////////////////////////////////////////////////
            Route::resource('roles','RoleController');
    /////////////////////////////// end roles Route//////////////////////////////////////////////////////////

    /////////////////////////////// start users Route//////////////////////////////////////////////////////////
            Route::resource('users','UserController');
    /////////////////////////////// end users Route//////////////////////////////////////////////////////////

    /////////////////////////////// start invoices_reports Route//////////////////////////////////////////////////////////
            Route::get('invoices_reports','InvoicesReportController@getReport')->name('get.report');
            Route::post('search_reports','InvoicesReportController@searchReport')->name('search.report');
    /////////////////////////////// end invoices_reports Route//////////////////////////////////////////////////////////

    /////////////////////////////// start customer_reports Route//////////////////////////////////////////////////////////
            Route::get('customer_reports','CustomerReportController@getCustomerReport')->name('get.customer.report');
            Route::post('search_Customer','CustomerReportController@searchCustomerReport')->name('search.customer.report');
    /////////////////////////////// end customer_reports Route//////////////////////////////////////////////////////////



});




