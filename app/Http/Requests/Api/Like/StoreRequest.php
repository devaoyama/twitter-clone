<?php

namespace App\Http\Requests\Api\Like;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
            'message_id' => [
                'required',
                Rule::unique('likes')
                    ->where('user_id', $this->user()->id)
                    ->where('message_id', $this->message_id),
            ],
        ];
    }

    public function messages()
    {
        return [
            'message_id.unique' => '既にいいねが存在しています。',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Failed validation',
            'errors' => $validator->errors(),
        ], 400));
    }
}
