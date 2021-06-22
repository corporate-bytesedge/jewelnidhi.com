<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class ProductCatalogRequest extends FormRequest
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
          
        if($request->old_image=='') {
            $image = 'required';
        } else {
            $image = '';
        }
          
        return [
            'image' => $image,
            'sku' => 'required|max:255|unique:product_catalogs,sku,'.$this->segment(4).',id',
             
            'weight' => 'required|numeric',
            'diamond_weight' => 'required|numeric',
            'is_active' => 'required'
        ];
    }
}
