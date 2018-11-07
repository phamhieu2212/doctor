<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class QuickBloxRequest extends FormRequest
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
            'username'          => 'required|string',
            'password'      => 'required|min:8',
            'token'    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'password.min' => 'password is too sort',
        ];
    }
}
