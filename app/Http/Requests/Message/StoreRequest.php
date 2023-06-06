<?php

namespace App\Http\Requests\Message;

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
            'files' => 'nullable|array',
            'uuid' => 'required',
            'from' => 'required|integer',
            'to' => 'required|integer',
            'message' => 'required_without:files|nullable|string',
            'replyMessage' => 'nullable'
        ];
    }
}
