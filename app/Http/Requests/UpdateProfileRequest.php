<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateProfileRequest extends Request
{
    protected $rules = [
        'name'  => 'required|string',
        'email' => 'required|email',
        'gender' => 'required|string',
        'interestedInGender' => 'required|string',
        'birthDate' => 'required|string'
    ];

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
        return $this->rules;
    }
}
