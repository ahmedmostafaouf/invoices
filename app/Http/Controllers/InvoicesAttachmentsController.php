<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoicesAttachmentsRequest;
use App\Models\Invoices_attachments;
use App\Traits\General;
use Illuminate\Http\Request;

class InvoicesAttachmentsController extends Controller
{
    use General;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function store(InvoicesAttachmentsRequest $request)
    {

        $path= $this->SaveImages($request->file('file_name'),'assets/images/attachments/'.$request->invoice_number);
        $attachments=Invoices_attachments::create([
            'invoice_number'=>$request->invoice_number,
            'invoice_id'=>$request->invoice_id,
            'file_name'=>$path,
            'created_by'=>auth()->user()->name
        ]);
        return redirect()->route('invoices.details',$request->invoice_id)->with(['success' => 'تم اضافه المرفق بنجاح']);

    }


    public function show(Invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoices_attachments  $invoices_attachments
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoices_attachments  $invoices_attachments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoices_attachments  $invoices_attachments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoices_attachments $invoices_attachments)
    {
        //
    }
}
