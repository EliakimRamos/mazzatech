<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorUpdateRequest extends FormRequest
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
            'specialty' => 'required',
            'phone'     => 'required|min:11',
            'crm'       => 'required',
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,'.request()->input('user_id'),
            'password'  => 'same:confirm-password',
            'roles'     => 'required'
        ];
    }
}
