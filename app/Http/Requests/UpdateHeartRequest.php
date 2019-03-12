<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateHeartRequest extends Request
{
    protected $rules = [
        'status' => 'required|integer|in:1,2'
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
