<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use Illuminate\Http\Request;

class InvoicesReportController extends Controller
{
    public function getReport(){
        return view('invoices.invoices_reports');
    }
    public function searchReport(Request $request){
        $radio=$request->radio;
        //search type of invoices
        if($radio == 1){
            $type=$request->type;
            //search when use type only
            if($request->type &&$request->start_at==''&&$request->end_at==''){
                if ($request->type == '4' &&$request->start_at==''&&$request->end_at=='' ){
                     $invoices=Invoices::all();
                    return view('invoices.invoices_reports',compact('invoices','type'));
                }
                 $invoices=Invoices::where('status',$request->type)->get();
                 return view('invoices.invoices_reports',compact('invoices','type'));

            }
            else{
                if ($request->type=='4'){
                    $start_at=date($request->start_at);
                    $end_at=date($request->end_at);
                    $invoices = invoices::whereBetween('invoices_date',[$start_at,$end_at])->get();
                    return view('invoices.invoices_reports',compact('invoices','type','start_at','end_at'));
                }
                  $start_at=date($request->start_at);
                   $end_at=date($request->end_at);
                   $invoices = invoices::whereBetween('invoices_date',[$start_at,$end_at])->where('status','=',$request->type)->get();
                return view('invoices.invoices_reports',compact('invoices','type','start_at','end_at'));
            }

        }else{
            $invoices=Invoices::where('invoices_num',$request->invoice_number)->get();
            return view('invoices.invoices_reports',compact('invoices'));

        }
    }
}
