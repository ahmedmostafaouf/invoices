<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoicesAttachmentsRequest;
use App\Models\Invoices_attachments;
use App\Traits\General;
use Illuminate\Http\Request;

class InvoicesAttachmentsController extends Controller
{
    use General;

    public function store(InvoicesAttachmentsRequest $request)
    {

        $path= $this->SaveImages($request->file('file_name'),'assets/images/attachments/'.$request->invoice_number);
        $attachments=Invoices_attachments::create([
            'invoice_number'=>$request->invoice_number,
            'invoice_id'=>$request->invoice_id,
            'file_name'=>$path,
            'created_by'=>auth()->user()->name
        ]);
        return redirect()->route('invoices.details',$request->invoice_id)->with(['success'=>"تم الأضافة بنجاح"]);

    }


}
