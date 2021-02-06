<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoicesAttachmentsRequest extends FormRequest
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
           "file_name"=>"required|mimes:jpg,png,jpeg,pdf",
        ];
    }
    public function messages()
    {
        return[
            'file_name.required'=>"يجب ادخال ملف ",
            "file_name.mimes"=>"الملف يجب ن يكون pdf او jpg او png او jpeg"

        ];
    }
}
