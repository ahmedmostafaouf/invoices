<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\Invoices_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceArchiveController extends Controller
{

    public function index()
    {
        $invoices = Invoices::onlyTrashed()->get();
        return view('invoices.archive',compact('invoices'));

    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $id = $request->invoice_id;
        // الغاء الارشقة وتحويله للعادي
        $invoices=Invoices::withTrashed()->where('id',$id)->restore();
        return redirect()->route('invoices.index')->with(['success'=>" تم الغاء الارشفة بنجاح "]);
    }


    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices=Invoices::withTrashed()->where('id',$id)->first();
        $Details=Invoices_attachments::where('invoice_id',$id)->first();
            if(!empty($Details)){
                Storage::disk('attachments')->deleteDirectory($Details->invoice_number);
            }
        $invoices->forceDelete();
        return redirect()->route('archive.index')->with(['success'=>" تم حذف الارشفة بنجاح "]);


    }
}
