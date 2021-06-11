<?php

namespace App\Http\Requests\Api\Like;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DestroyRequest extends FormRequest
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
            'message_id' => [
                'required',
                Rule::exists('likes')
                    ->where('user_id', $this->user()->id)
                    ->where('message_id', $this->message_id),
            ],
        ];
    }

    public function messages()
    {
        return [
            'message_id.exists' => 'いいねが存在しません。',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json([
            'message' => 'Failed validation',
            'errors' => $errors,
        ], 400));
    }
}
