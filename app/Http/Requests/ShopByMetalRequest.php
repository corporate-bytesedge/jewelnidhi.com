<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopByMetalRequest extends FormRequest
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
            'category_id' => 'required',
            'name' => 'required',
            'is_active' => 'required'
        ];
    }
    public function messages(){
        return [
            'category_id.required' => 'Please select category',
            'name.required' => 'Please enter name',
            'is_active.required' => 'Please select status'
        ];
    }
}
