<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class SubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
    {
        return $this->user()->hasRole(User::PATIENT_ROLE);
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
