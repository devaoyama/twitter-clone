<?php

namespace App\Http\Requests\Message;

use App\Models\Message;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'content' => 'required|string|max:140',
        ];
    }

    public function makeMessage(): Message
    {
        return new Message($this->validated());
    }
}
