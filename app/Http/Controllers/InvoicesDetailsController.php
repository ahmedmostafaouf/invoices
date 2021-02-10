<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoicesAttachmentsRequest;
use App\Http\Requests\InvoicesRequest;
use App\Models\Invoices;
use App\Models\Invoices_attachments;
use App\Models\Invoices_details;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{
    use General;


   public function openFile($invoice_number,$file_name){
        $files=Storage::disk('attachments')->getDriver()->getAdapter()->applyPathPrefix($invoice_number.'/'.$file_name);
        return response()->file($files);
   }
    public function getFile($invoice_number,$file_name){
        $files=Storage::disk('attachments')->getDriver()->getAdapter()->applyPathPrefix($invoice_number.'/'.$file_name);
        return response()->download($files);
    }
    public function show($id)
    {
        try {
            $invoices=Invoices::findOrFail($id);
            $invoices_details  = invoices_Details::where('invoices_id',$id)->get();
            $attachments  = Invoices_attachments::where('invoice_id',$id)->get();

            if (!$invoices){
                return redirect()->route('invoices.index')->with(['error' => 'Sorry This item Not Found']);
            }
       // when click a link in notify update to read and remove in list
            $userUnreadNotification=auth()->user()->unreadNotifications;
            foreach ($userUnreadNotification as $noty){
                if($noty->data['invoices_id'] == $invoices->id ){
                    $noty->markAsRead();
                }
            }

            return view('invoices.invoices_details',compact('invoices','invoices_details','attachments'));
        }catch (\Exception $ex){
            return redirect()->route('invoices.index')->with(['error' => 'Sorry Something went wrong']);
        }
    }


    public function destroy(Request $request)

    {
        try {
            $id =$request->file_id;
            $attachments=Invoices_attachments::findOrFail($id);
            if(!$attachments){
                return redirect()->route('invoices.index')->with(['error' => 'المرفق غير موجود']);
            }
            $attachments->delete();
            Storage::disk('attachments')->delete($request->invoice_number.'/'.$request->file_name);
            return redirect()->route('invoices.index')->with(['success' => 'تم حذف الملف بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('invoices.index')->with(['error' => 'المرفق غير موجود']);

        }

    }

}
