<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
     * リクエストに適用するバリデーションルールを取得
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment.*' => 'numeric',
            'paymentSum' => 'numeric'
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
            'payment.*.numeric' => '金額は半角数字で入力して下さい。',
            'paymentSum.numeric' => '金額は半角数字で入力して下さい。'
        ];
    }
}
