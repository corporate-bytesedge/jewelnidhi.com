<?php

namespace App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class MetalPuirtyRequest extends FormRequest
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
            'metal_id' => 'required',
            'name' => 'required|max:255|unique:metal_puirties,name,'.$request->id,
            'is_active' => 'required'
        ];
    }
}
