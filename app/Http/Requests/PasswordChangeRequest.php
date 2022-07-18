<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordChangeRequest extends FormRequest
{
    /**
     * パスワード最小文字数
     */
    const PASSWORD_MIN = 8;

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
            'nowPassword' => ['required', 'current_password'],
            'newPassword' => ['required', 'confirmed', Password::min(self::PASSWORD_MIN)],
            'newPassword_confirmation' => ['required'],
        ];
    }

    /**
     * 定義済みバリデーションルールのエラーメッセージ取得
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nowPassword.required' => '現在のパスワードは必須です。',
            'nowPassword.current_password' => '現在のパスワードが一致しません',
            'newPassword.required' => '新しいパスワードは必須です。',
            'newPassword.min' => '新しいパスワードは' . self::PASSWORD_MIN . '文字以上で指定してください。',
            'newPassword.confirmed' => '新しいパスワードと新しいパスワード(確認)が一致しません。',
            'newPassword_confirmation.required' => '新しいパスワード(確認)は必須です。',
        ];
    }
}
