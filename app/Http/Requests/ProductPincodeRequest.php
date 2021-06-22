<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class ProductPincodeRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'name' => 'required|numeric|digits:6|unique:product_pin_codes,name,'.$this->segment(4).',id',
            'is_active' => 'required'
        ];
    }
     
    public function messages()
    {
        return [
            'name.required' => 'Pincode is required',
            'name.unique' => 'Pincode has already been taken',
            'name.numeric' => 'Pincode must be number',
            'name.digits' => 'Pincode must be 6 digit',
            'is_active.required' => 'Status is required',
        ];
    }
}
