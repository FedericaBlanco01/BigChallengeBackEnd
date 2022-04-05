<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddSubmissionRequest extends FormRequest
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

    public function rules(): array
    {
        return [
            'weight' => 'required|numeric|digits_between:2,3',
            'height' => 'required|numeric|digits:3',
            'symptoms'=> 'required',
        ];
    }
}
