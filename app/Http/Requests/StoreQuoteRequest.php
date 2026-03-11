<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email'         => 'required|email|max:255',
            'phone'         => 'nullable|string|max:30',
            'country'       => 'nullable|string|max:100',
            'trip_types'    => 'nullable|array',
            'trip_types.*'  => 'string',
            'destinations'  => 'nullable|array',
            'destinations.*'=> 'string',
            'experiences'   => 'nullable|array',
            'experiences.*' => 'string',
            'occasions'     => 'nullable|array',
            'occasions.*'   => 'string',
            'accommodation' => 'nullable|string|max:100',
            'adults'        => 'nullable|integer|min:1|max:50',
            'children'      => 'nullable|integer|min:0|max:50',
            'arrival_date'  => 'nullable|date|after:today',
            'message'       => 'nullable|string|max:2000',
        ];
    }
}