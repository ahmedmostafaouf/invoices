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
use Complex\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesExport;

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
        try {
            DB::beginTransaction();
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
            DB::commit();
            // send notification and email
            $user = User::first();
            Notification::send($user, new AddInvoice($invoices_id));
            $users=User::get();
            $invoices=Invoices::latest()->first();
            Notification::send($users,new \App\Notifications\AddIvoicesNotify($invoices));
            //end
            return redirect()->route('invoices.index')->with(['success'=>"تم الأضافة بنجاح"]);
        }catch (\Exception $ex){
            DB::rollback();
            return redirect()->route('invoices.index')->with(['error' => 'حصل حطأ ما في الحفظ']);
        }

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
       try {
           $invoices=Invoices::findOrFail($id);
           if(!$invoices){
               return redirect()->route('invoices.index')->with(['error' => 'Sorry This item Not Found']);
           }
           return view('invoices.invoices_status',compact('invoices'));
       }catch (\Exception $ex){
           return redirect()->route('invoices.index')->with(['error' => 'Sorry Something went wrong']);
       }

   }
   public function statusUpdate(Request $request,$id){
       try {
           $invoices=Invoices::findOrFail($id);
           if($request->status==1){
               DB::beginTransaction();

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
               DB::beginTransaction();

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
           DB::commit();

           return redirect()->route('invoices.index')->with(['success'=>" تم تغير الحالة بنجاح "]);
       }catch (\Exception $ex){
           DB::rollBack();
           return redirect()->route('invoices.index')->with(['error' => 'Sorry Something went wrong']);

       }

   }

    public function edit($id)
    {
        try {
            $invoices=Invoices::findOrFail($id);
            if(!$invoices){
                return redirect()->route('invoices.index')->with(['error' => 'Sorry This item Not Found']);
            }
            $categories=Category::all();
            return view('invoices.edit_invoices',compact('invoices','categories'));

        }catch (\Exception $ex){
            return redirect()->route('invoices.index')->with(['error' => 'Sorry Something went wrong']);

        }

    }


    public function update(InvoicesRequest $request,$id)
    {
        try {
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
        }catch (\Exception $ex){
            return redirect()->route('invoices.index')->with(['error' => 'Sorry Something went wrong']);

        }
    }


    public function destroy(Request $request)
    {
        try {
            $id= $request->invoice_id;
            $invoices=Invoices::findOrFail($id);
            if(!$invoices){
                return redirect()->route('invoices.index')->with(['error'=>"حصل خطأ ما "]);
            }
            $Details=Invoices_attachments::where('invoice_id',$id)->first();
            if(!$request->page_id==2){
                if(!empty($Details)){
                    Storage::disk('attachments')->deleteDirectory($Details->invoice_number);
                }
                $invoices->forceDelete();
                return redirect()->route('invoices.index')->with(['success'=>" تم الحذف بنجاح "]);
            }else{
                $invoices->delete();
                return redirect()->route('archive.index')->with(['success'=>"تم الارشفة بنحاخ "]);
            }
        }catch (\Exception $ex){
            return redirect()->route('invoices.index')->with(['error' => 'Sorry Something went wrong']);

        }

    }
    public function deleteAllChecked(Request $request){

        try {
            $delete_all=explode(',',$request->delete_all_id);

            $Details=Invoices_attachments::WhereIn('invoice_id',$delete_all)->first();
            if(!$request->page_id==2) {
                if (!empty($Details)) {
                    Storage::disk('attachments')->deleteDirectory($Details->invoice_number);
                }
                $invoices=Invoices::WhereIn('id',$delete_all)->forceDelete();
                return redirect()->route('invoices.index')->with(['success'=>" تم الحذف بنجاح "]);

            }else{
                $invoices=Invoices::WhereIn('id',$delete_all)->Delete();
                return redirect()->route('archive.index')->with(['success'=>" تم الحذف بنجاح ونقلة للاشرفة  "]);
            }

        }catch (\Exception $ex){
            return redirect()->route('invoices.index')->with(['error' => 'Sorry Something went wrong']);

        }
    }
    public function getProduct($id){
       $products=Product::where('category_id',$id)->pluck('product_name',"id");
        return json_encode($products);
    } // ajax function

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
