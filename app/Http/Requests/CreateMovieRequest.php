<?php

declare(strict_types=1);

namespace App\Http\Requests;

class CreateMovieRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|unique:movies|max:255',
            'published' => 'boolean',
            'poster' => 'string|max:255',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Name is required!',
            'title.unique' => 'The name should be unique!',
        ];
    }
}
