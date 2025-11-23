<?php

namespace App\Http\Requests\Assignment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignment extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'min:1', 'max:10'],
            'deskripsi' => ['required', 'string', 'min:1', 'max:1000'],
            'topik' => ['required', 'string', 'min:1', 'max:5'],
            'status_id' => ['required', 'integer', 'between:1,3'],
            'priotity_id' => ['required', 'integer', 'between:1,3'],
            'tanggal_terakhir' => ['required', 'date', 'date_format:Y-m-d', 'after:today'],
        ];
    }
}
