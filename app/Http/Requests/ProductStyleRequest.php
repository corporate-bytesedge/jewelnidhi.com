<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class ProductStyleRequest extends FormRequest
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
        
        // if($request->old_image=='') {
        //     $image = 'required';
        // } else {
        //     $image = '';
        // }
         
        return [
            'category_id' => 'required',
            'name' => 'required|max:255',
            'hsn_code' => 'required|max:255',
            'is_active' => 'required',
             
            'image' => ''
        ];
    }

    public function messages() {
        return 
        [
            'category_id.required' => 'Please select category',
            'name.required' => 'Please enter name',
            'hsn_code.required' => 'Please enter HSN code',
            'is_active.required' => 'Please select status',
           
    
        ];
    }
}
