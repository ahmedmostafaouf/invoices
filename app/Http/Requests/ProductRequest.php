<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            "product_name"=>"required|max:50|unique:products,product_name,".$this->id,
            "description"=>"required|max:200|min:10",

        ];
    }
    public function messages()
    {
        return [
            "required"=>"هذا الحقل مطلوب",
            "min"=>"يجب ادخال ملاحظه علي الاقل 12 حرف",
            "max"=>"يجب الا يزيد عن 200 حرف",
            "unique"=>"هذا الاسم موجود من قبل "
        ];
    }
}
