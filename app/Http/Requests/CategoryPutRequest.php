<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryPutRequest extends FormRequest
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
     * バリーデーションのためにデータを準備
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // 全角スペースを半角スペースに変換します
        $categoryNames = $this->categoryName;
        $this->merge([
            'categoryName' => array_map(
                function ($categoryNames) {
                    return mb_convert_kana($categoryNames, 's');
                },
                $categoryNames
            )
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'categoryName.*' => 'required'
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
            'categoryName.*.required' => 'カテゴリー名は必須です。'
        ];
    }
}
