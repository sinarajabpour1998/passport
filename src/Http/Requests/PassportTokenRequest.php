<?php

namespace SRA\Passport\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PassportTokenRequest extends FormRequest
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
            'username' => ["required", "min:6", "string"],
            'password' => ["required", 'different:current_password', "min:8", "string"],
            'password_confirmation' => ['required', 'same:password', 'min:8']
        ];
    }
}
