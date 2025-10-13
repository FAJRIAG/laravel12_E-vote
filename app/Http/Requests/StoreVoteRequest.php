<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoteRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'votes' => ['required','array','min:1'], // key: position_id, value: candidate_id
            'votes.*.position_id'  => ['required','exists:positions,id'],
            'votes.*.candidate_id' => ['required','exists:candidates,id'],
        ];
    }
}
