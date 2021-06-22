<?php

namespace App\Http\Requests;
use Illuminate\Http\Request;
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
    public function rules(Request $request)
    {
        
         if($request->save_draft_btn == 'save_draft') {
            return [
                'category' => 'required',
                'style_id' => 'required|array',
                'style_id.*' => 'required',
                'jn_web_id' => 'required|max:255|unique:products,jn_web_id,'.$this->segment(3).',id',
                'name' => 'required|max:191'
                
                
                 
            ];
         } else {
            return [
                'category' => 'required',
                'style_id' => 'required|array',
                'style_id.*' => 'required',
                'jn_web_id' => 'required|max:255|unique:products,jn_web_id,'.$this->segment(3).',id',
                'name' => 'required|max:191',
                'metal_id' => 'required',
                'old_price' => 'required',
                'in_stock' => 'required'
                 
            ];
         }
        
    }

    public function messages() {
         
        return [
            'category.required' => 'Category field is required',  
            'style_id.required' => 'Style field is required',
            'jn_web_id.required' => 'JN Web ID is required',
            'jn_web_id.unique' => 'Product ID already exist',
            'name.required' => 'Product name is required',
            'metal_id.required' => 'Product metal is required',
            'old_price.required' => 'Selling price is required',
            'in_stock.required' => 'Please enter product quantity'
            
        ];
        
    }
}
