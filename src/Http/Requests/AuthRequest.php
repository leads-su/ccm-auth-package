<?php

namespace ConsulConfigManager\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AuthRequest
 *
 * @package ConsulConfigManager\Auth\Http\Requests
 */
class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'emailOrUsername'       =>  ['required', 'string'],
            'password'              =>  ['required', 'string', 'min:8', 'max:32'],
        ];
    }
}
