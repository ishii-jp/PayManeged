<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @TODO ユニットテスト未実施クラス(ブラウザでの動作確認は済み)
 */
class MyPageUserEditPostRequest extends FormRequest
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
     * @TODO 本当はlaravelのemailルール(email:rfc,dns)を適用して不適切なメールアドレスをバリデーションすべきですが、
     * 学習の一環で作成しているアプリなので、ここまで厳密には見ないです。
     * @return array
     */
    public function rules()
    {
        return [
            'userName' => 'required',
            'userEmail' => 'required'
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
            'userName.required' => '名前は必須です。',
            'userEmail.required' => 'メールアドレスは必須です。'
        ];
    }
}
