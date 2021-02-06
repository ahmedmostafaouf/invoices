<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices_details extends Model
{
    protected $fillable=[
        'id',
        'invoice_number',
        'invoices_id',
        'product',
        'category',
        'status',
        "user",
        'note',
        'Payment_Date'

    ];
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
