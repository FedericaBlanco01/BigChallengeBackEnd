<?php

namespace App\Http\Requests;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AssignSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var Submission $submission */
        $submission = $this->route('submission');

        return $this->user()->hasRole(User::DOCTOR_ROLE);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
