<?php

namespace App\Http\Requests;

use App\Rules\ValidIsbn;
use Illuminate\Foundation\Http\FormRequest;

abstract class BookRequest extends FormRequest
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
            'title' => 'required|max:255',
            'isbn' => ['required', 'unique:books', ValidIsbn::class],
            'published_at' => 'required|date:YYYY-MM-DD',
        ];
    }
}
