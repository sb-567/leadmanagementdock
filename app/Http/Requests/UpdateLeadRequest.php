<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeadRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    // app/Http/Requests/UpdateLeadRequest.php
    public function rules(): array
    {
    //    dd($this->all());
       
        return [
            'name'             => 'required|string|max:100',
            'phone'            => 'required|digits_between:10,15',
            'email'            => 'nullable|email',
            'source'           => 'nullable|string|max:50',
            'product_interest' => 'nullable|string|max:100',
            'city'             => 'nullable|string|max:50',
            'remarks'          => 'nullable|string',
            'status'           => 'required|in:new,interested,not_interested,callback,converted,junk',
            'assigned_to'      => 'nullable|exists:users,id',
        ];
    }
}
