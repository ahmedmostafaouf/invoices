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

    public function update(Request $request, $id)
    {
        try {
            $id = $request->invoice_id;
            // الغاء الارشقة وتحويله للعادي
            $invoices=Invoices::withTrashed()->where('id',$id)->restore();
            return redirect()->route('invoices.index')->with(['success'=>" تم الغاء الارشفة بنجاح "]);
        }catch (\Exception $ex){
            return redirect()->route('invoices.index')->with(['error'=>" حذث حطأ اثناء عمليه الغاء الارشفة "]);

        }

    }

    public function destroy(Request $request)
    {
        try {
            $id = $request->invoice_id;
            $invoices=Invoices::withTrashed()->where('id',$id)->first();
            $Details=Invoices_attachments::where('invoice_id',$id)->first();
            if(!empty($Details)){
                Storage::disk('attachments')->deleteDirectory($Details->invoice_number);
            }
            $invoices->forceDelete();
            return redirect()->route('archive.index')->with(['success'=>" تم حذف الارشفة بنجاح "]);
        }catch (\Exception $ex){
            return redirect()->route('archive.index')->with(['error'=>" حذث خطأ عند حذف الارشفة حاول مرة اخري "]);

        }

    }
}
