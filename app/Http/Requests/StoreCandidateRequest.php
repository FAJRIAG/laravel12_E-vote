<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidateRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->is_admin === true; }

    public function rules(): array
    {
        return [
            'election_id' => ['required','exists:elections,id'],
            'position_id' => ['required','exists:positions,id'],
            'name'        => ['required','string','max:120'],
            'vision'      => ['nullable','string'],
            'mission'     => ['nullable','string'],
            'order'       => ['nullable','integer','min:0'],
            'photo'       => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
        ];
    }
}
