<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGameRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|min:5|max:50',
            'image' => 'nullable|max:255',
            'platform' => 'nullable',
            'price' => 'nullable',
            'release_date' => 'nullable|date',
            'description' => 'nullable'
        ];
    }
}
