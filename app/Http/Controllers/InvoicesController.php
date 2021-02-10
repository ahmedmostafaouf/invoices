<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoicesRequest;
use App\Models\Category;
use App\Models\Invoices;
use App\Models\Invoices_attachments;
use App\Models\Invoices_details;
use App\Models\Product;
use App\Notifications\AddInvoice;
use App\Traits\General;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesExport;
use function GuzzleHttp\Promise\all;

class InvoicesController extends Controller
{
    use General;

    public function index()
    {
        $invoices=Invoices::all();
       return view('invoices.invoice',compact('invoices'));
    }


    public function create()
    {
        $categories=Category::all();
        return view('invoices.create_invoices',compact('categories'));
    }

    public function store(InvoicesRequest $request)
    {
         Invoices::create([
           "invoices_num"=>$request->invoice_number,
             "invoices_date"=>$request->invoice_Date,
             "due_date"=>$request->due_date,
             "category_id"=>$request->category,
             "product"=>$request->product,
             "discount"=>$request->discount,
             "rate_vat"=>$request->rate_vat,
             "value_vat"=>$request->value_vat,
             "total"=>$request->total,
             "note"=>$request->note,
             "user"=>auth()->user()->name,
             "status"=>0,
             'amount_collection' => $request->amount_collection,
             'amount_commission' => $request->amount_commission,
         ]);
         $invoices_id=Invoices::latest()->first()->id;
         Invoices_details::create([
             "invoices_id"=>$invoices_id,
             "invoice_number"=>$request->invoice_number,
             "category"=>$request->category,
             "product"=>$request->product,
             "status"=>0,
             "note"=>$request->note,
             "user"=>auth()->user()->name,
         ]);
         if($request ->file('pic')){
             $invoices_id=Invoices::latest()->first()->id;
                         $path=$this->SaveImages($request->file('pic'),'assets/images/attachments/'.$request->invoice_number);
             Invoices_attachments::create([
              "invoice_number"=>$request->invoice_number,
              "file_name"=>$path,
              "created_by"=>auth()->user()->name,
              "invoice_id"=>$invoices_id
             ]);
         }
        $user = User::first();
        Notification::send($user, new AddInvoice($invoices_id));
        $users=User::get();
        $invoices=Invoices::latest()->first();
        Notification::send($users,new \App\Notifications\AddIvoicesNotify($invoices));

        return redirect()->route('invoices.index')->with(['success'=>"تم الأضافة بنجاح"]);


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

            return view('invoices.invoices_details',compact('invoices','invoices_details','attachments'));

        }catch (\Exception $ex){
            return redirect()->route('invoices.index')->with(['error' => 'Sorry Something went wrong']);
        }

    }
   public function statusShow($id){
        $invoices=Invoices::findOrFail($id);
        if(!$invoices){
            return redirect()->route('invoices.index')->with(['error' => 'Sorry This item Not Found']);
        }
        return view('invoices.invoices_status',compact('invoices'));
   }
   public function statusUpdate(Request $request,$id){
        $invoices=Invoices::findOrFail($id);
        if($request->status==1){
            $invoices->update([
               'status'=>$request->status,
                'Payment_Date'=>$request->Payment_Date
            ]);
            Invoices_details::create([
                "invoices_id"=>$request->invoices_id,
                "invoice_number"=>$request->invoice_number,
                "category"=>$request->category,
                "product"=>$request->product,
                "status"=>$request->status,
                "note"=>$request->note,
                "Payment_Date"=>$request->Payment_Date,
                "user"=>auth()->user()->name,
            ]);
        }
        else{
            $invoices->update([
                'status'=>$request->status,
                'Payment_Date'=>$request->Payment_Date
            ]);
            Invoices_details::create([
                "invoices_id"=>$request->invoices_id,
                "invoice_number"=>$request->invoice_number,
                "Payment_Date"=>$request->Payment_Date,
                "category"=>$request->category,
                "product"=>$request->product,
                "status"=>$request->status,
                "note"=>$request->note,
                "user"=>auth()->user()->name,
            ]);
        }
       return redirect()->route('invoices.index')->with(['edit_status'=>" تم تغير الحالة بنجاح "]);


   }

    public function edit($id)
    {
        $invoices=Invoices::findOrFail($id);
        if(!$invoices){
            return redirect()->route('invoices.index')->with(['error' => 'Sorry This item Not Found']);
        }
        $categories=Category::all();
        return view('invoices.edit_invoices',compact('invoices','categories'));
    }


    public function update(InvoicesRequest $request,$id)
    {
        $invoices=Invoices::findOrFail($id);
        if(!$invoices){
            return redirect()->route('invoices.index')->with(['error' => 'Sorry This item Not Found']);
        }

            $user = $invoices->update([
                "invoices_num"=>$request->invoice_number,
                "invoices_date"=>$request->invoice_Date,
                "due_date"=>$request->due_date,
                "category_id"=>$request->category,
                "product"=>$request->product,
                "discount"=>$request->discount,
                "rate_vat"=>$request->rate_vat,
                "value_vat"=>$request->value_vat,
                "total"=>$request->total,
                "note"=>$request->note,
                "user"=>auth()->user()->name,
                'amount_collection' => $request->amount_collection,
                'amount_commission' => $request->amount_commission,
            ]);
        return redirect()->route('invoices.index')->with(['success'=>"تم التعديل بنجاح"]);


    }


    public function destroy(Request $request)
    {
       $id= $request->invoice_id;
       $invoices=Invoices::findOrFail($id);
       if(!$invoices){
           return redirect()->route('invoices.index')->with(['delete_invoice'=>"حصل خطأ ما "]);
       }
        $Details=Invoices_attachments::where('invoice_id',$id)->first();
       if(!$request->page_id==2){
           if(!empty($Details)){
               Storage::disk('attachments')->deleteDirectory($Details->invoice_number);
           }
           $invoices->forceDelete();
           return redirect()->route('invoices.index')->with(['delete_invoice'=>" تم الحذف بنجاح "]);
       }else{
           $invoices->delete();
           return redirect()->route('archive.index')->with(['delete_invoice'=>"تم الارشفة بنحاخ "]);
       }
    }
    public function deleteAllChecked(Request $request){

        $delete_all=explode(',',$request->delete_all_id);

        $Details=Invoices_attachments::WhereIn('invoice_id',$delete_all)->first();
        if(!$request->page_id==2) {
            if (!empty($Details)) {
                Storage::disk('attachments')->deleteDirectory($Details->invoice_number);
            }
            $invoices=Invoices::WhereIn('id',$delete_all)->forceDelete();
            return redirect()->route('invoices.index')->with(['delete_invoice'=>" تم الحذف بنجاح "]);

        }else{
            $invoices=Invoices::WhereIn('id',$delete_all)->Delete();
            return redirect()->route('archive.index')->with(['delete_invoice'=>" تم الحذف بنجاح ونقلة للاشرفة  "]);
        }



    }
    public function getProduct($id){
       $products=Product::where('category_id',$id)->pluck('product_name',"id");
        return json_encode($products);
    }
    public function getInvoicePaid(){
        $invoices=Invoices::where('status',1)->get();
        return view('invoices.invoice_paid',compact('invoices'));
    }
    public function getInvoiceUnPaid(){
        $invoices=Invoices::where('status',0)->get();
        return view('invoices.invoice_paid',compact('invoices'));
    }
    public function getInvoicePartial(){
        $invoices=Invoices::where('status',2)->get();
        return view('invoices.invoice_paid',compact('invoices'));
    }
    public function invoice_print($id){
        $invoices = Invoices::findOrFail($id);
        return view('invoices.invoice_print',compact('invoices'));
    }
    public function export()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }
    public function markAsRead_all(){
       $userUnreadNotification=auth()->user()->unreadNotifications;
       if($userUnreadNotification){
           $userUnreadNotification->markAsRead();
           return back();
       }
    }

}
