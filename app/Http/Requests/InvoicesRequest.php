<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoicesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          "invoice_number"=>"required|unique:invoices,invoices_num,".$this->id,
            "invoice_Date"=>"required",
            "due_date"=>"required",
            "product"=>"required",
            "category"=>"required",
            "amount_collection"=>"required",
            "amount_commission"=>"required",
            "discount"=>"required",
            "rate_vat"=>"required",
        ];
    }
}
