<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePRFRequest extends FormRequest
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
            'date_request' => 'required|date',
            'date_needed' => 'required|date',
            'status' => 'required',
            'full_name' => 'required',
            'contact' => 'required',
            'position' => 'required',
            'department' => 'required',
            'branch' => 'required',
            'urgency' => 'required',
            'request_type' => 'required',
            'request_details' => 'required',
            'upload_pdf' => 'required|array',
            'upload_pdf.*' => 'file|mimes:pdf',
            'next_department' => 'required',
            'assign_employee' => 'required',
        ];
    }
}
