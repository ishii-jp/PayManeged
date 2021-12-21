<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    private $min = 0;
    private $max = 99999999;
    private $pattern = '/^[0-9]+$/';

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
            'payment.*' => "numeric|regex:{$this->pattern}|between:{$this->min}, {$this->max}",
            'paymentSum' => "numeric|regex:{$this->pattern}|between:{$this->min}, {$this->max}"
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
            'payment.*.numeric' => '半角数字で入力して下さい。',
            'paymentSum.numeric' => '半角数字で入力して下さい。',
            'payment.*.regex' => '使用できない文字が含まれています。',
            'paymentSum.regex' => '使用できない文字が含まれています。',
            'payment.*.between' => "入力できる金額は¥{$this->min}から¥{$this->max}までです。",
            'paymentSum.between' => "入力できる金額は¥{$this->min}から¥{$this->max}までです。"
        ];
    }
}
