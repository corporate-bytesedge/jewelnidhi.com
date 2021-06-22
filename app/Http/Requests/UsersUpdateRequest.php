<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersUpdateRequest extends FormRequest
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

    protected function getValidatorInstance()
    {
        $data = $this->all();
        $data['name'] = trim($data['name']);
        $data['name'] = ucwords(strtolower($data['name']));
        $this->getInputSource()->replace($data);

        /*modify data before send to validator*/

        return parent::getValidatorInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name'=>'required|max:50',
            'email' => 'required_without:phone_number',
            'phone_number' => 'required_without:email',
            'password'=>'confirmed|required|min:6|max:80',
            'photo'=>'image'
        ];

        
    }

    public function messages()
    {
        return [
            'photo.image' => 'The photo is invalid.'
        ];
    }
}
