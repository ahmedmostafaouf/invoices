<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Invoices;
use Illuminate\Http\Request;

class CustomerReportController extends Controller
{
    public function getCustomerReport(){
        try {
            $categories=Category::all();
            return view('invoices.customer_reports',compact('categories'));
        }catch (\Exception $ex){
            return abort('500');
        }

    }
    public function searchCustomerReport(Request $request){
        try {
            if($request->category && $request->product && $request->start_at==''&&$request->end_at==''){
                $invoices=Invoices::where('product',$request->product)->where('category_id',$request->category)->get();
                $categories=Category::all();
                return view('invoices.customer_reports',compact('invoices','categories'));
            }
            else{
                $start_at =date($request->start_at);
                $end_at =date($request->end_at);
                $categories=Category::all();
                $invoices=Invoices::whereBetween('invoices_date',[$start_at,$end_at])->where('product',$request->product)->where('category_id',$request->category)->get();
                return view('invoices.customer_reports',compact('invoices','categories','start_at','end_at'));

            }
        }catch (\Exception $ex){
            return redirect()->route('get.customer.report')->withErrors(['error' => 'حدث خطأ ما يتم معالجه قريبا حاول في وقت اخر']);
        }


    }
}
