<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoices extends Model
{
    use SoftDeletes;
    protected $fillable=[
      'id',
      'invoices_num',
      'invoices_date',
        'due_date',
        'product',
        'category_id',
        'discount',
        'rate_vat',
        'value_vat',
        'total',
        'status',
        "user",
        'note',
        "amount_collection",
        "amount_commission",
        "Payment_Date"
    ];
    public function category(){
        return $this->belongsTo('App\Models\Category');
    }
    public function getStatus(){
         if( $this->status == 1) {
           return "مدفوعة";
        }else if( $this->status == 2 ) {
             return "مدفوعة جزئيا";
         }else{
           return "غير مدفوعة";
         }
    }
}
