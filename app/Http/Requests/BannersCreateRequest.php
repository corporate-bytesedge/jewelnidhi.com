<?php

namespace App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BrandsUpdateRequest;

class BannersCreateRequest extends FormRequest
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
        if(!$request->input('old_image')) {
            $imagerules = 'mimes:jpeg,jpg,png,gif|required' ;
        } else {
            $imagerules = '';
        }
        
        return [
             
            'photo' => $imagerules,
            'priority'=>'numeric',
            'link'=>'required|url',
            'priority'=>'required',
            
        ];
    }

    public function messages()
    {
        return [
            'photo.required' => 'The image is required.',
            'link.required' => 'Please enter url.',
            'link.url' => 'Please enter valid url.',
            'priority.required' => 'Please enter order by.',
            
        ];
    }
}
