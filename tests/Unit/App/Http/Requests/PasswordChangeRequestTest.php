<?php

namespace Tests\Unit\App\Http\Requests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PasswordChangeRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

/**
 * PasswordChangeRequestTest
 */
class PasswordChangeRequestTest extends TestCase
{
    use RefreshDatabase;

    private string $nowPassword = 'password';
    private string $newPassword = 'newpassword';
    private User $user;

    /**
     * setUp
     *
     * テスト用ユーザーを作成します。
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        // DB接続先がテスト用に切り替わっているか確認
        // dd(env('APP_ENV'), env('DB_DATABASE'), env('DB_CONNECTION'));

        $this->user = User::factory()->create(['password' => $this->passwordMake($this->nowPassword)]);
    }

    /**
     * 文字列をハッシュ化して返します
     *
     * @param string $password パスワード
     * @return string ハッシュ化された文字列
     */
    private function passwordMake(string $password): string
    {
        return Hash::make($password);
    }

    /**
     * @test
     * @TODO nowPasswordのcurrent_passwordのバリデートがUTで現状通せていない。
     * setUpで作成したユーザーでログイン済みの状態にすることができれば下のテストコードが成功するはず。
     */
    // public function 全ての入力値が正常値の場合バリデーションに成功すること(): void
    // {
    //     $passwordChangeRequest = new PasswordChangeRequest;
    //     $inputValue = [
    //         'nowPassword' => $this->nowPassword,
    //         'newPassword' => $this->newPassword,
    //         'newPassword_confirmation' => $this->newPassword
    //     ];

    //     $validator = Validator::make($inputValue, $passwordChangeRequest->rules());

    //     $this->assertTrue($validator->passes());
    // }

    /**
     * @test
     */
    public function nowPasswordが空の場合バリデーションが失敗すること(): void
    {
        $passwordChangeRequest = new PasswordChangeRequest;
        $inputValue = [
            'nowPassword' => '',
            'newPassword' => $this->newPassword,
            'newPassword_confirmation' => $this->newPassword
        ];

        $validator = Validator::make($inputValue, $passwordChangeRequest->rules(), $passwordChangeRequest->messages());

        $this->assertFalse($validator->passes());
        $this->assertSame('現在のパスワードは必須です。', $validator->errors()->first('nowPassword'));
    }

    /**
     * @test
     */
    public function newPasswordとnewPassword_confirmationが空の場合バリデーションが失敗すること(): void
    {
        $passwordChangeRequest = new PasswordChangeRequest;
        $inputValue = [
            'nowPassword' => $this->nowPassword,
            'newPassword' => '',
            'newPassword_confirmation' => ''
        ];

        $validator = Validator::make($inputValue, $passwordChangeRequest->rules(), $passwordChangeRequest->messages());

        $this->assertFalse($validator->passes());
        $this->assertSame('新しいパスワードは必須です。', $validator->errors()->first('newPassword'));
        $this->assertSame('新しいパスワード(確認)は必須です。', $validator->errors()->first('newPassword_confirmation'));
    }

    /**
     * @test
     */
    public function newPasswordとnewPassword_confirmationの値が相違する場合バリデーションが失敗すること(): void
    {
        $passwordChangeRequest = new PasswordChangeRequest;
        $inputValue = [
            'nowPassword' => $this->nowPassword,
            'newPassword' => 'newpassword',
            'newPassword_confirmation' => 'neopassword'
        ];

        $validator = Validator::make($inputValue, $passwordChangeRequest->rules(), $passwordChangeRequest->messages());

        $this->assertFalse($validator->passes());
        $this->assertSame(
            '新しいパスワードと新しいパスワード(確認)が一致しません。',
            $validator->errors()->first('newPassword')
        );
    }

    /**
     * @test
     */
    public function newPasswordの値がログインしているユーザーのパスワードと異なる場合バリデーションが失敗すること(): void
    {
        $passwordChangeRequest = new PasswordChangeRequest;
        $inputValue = [
            'nowPassword' => $this->nowPassword . 'dummy',
            'newPassword' => $this->newPassword,
            'newPassword_confirmation' => $this->newPassword
        ];

        $validator = Validator::make($inputValue, $passwordChangeRequest->rules(), $passwordChangeRequest->messages());

        $this->assertFalse($validator->passes());
        $this->assertSame('現在のパスワードが一致しません', $validator->errors()->first('nowPassword'));
    }

    /**
     * @test
     */
    public function newPasswordが8文字以下の場合バリデーションが失敗すること(): void
    {
        $passwordChangeRequest = new PasswordChangeRequest;
        $newPassword = 'passwor';
        $inputValue = [
            'nowPassword' => $this->nowPassword,
            'newPassword' => $newPassword,
            'newPassword_confirmation' => $newPassword
        ];

        $validator = Validator::make($inputValue, $passwordChangeRequest->rules(), $passwordChangeRequest->messages());

        $this->assertFalse($validator->passes());
        $this->assertSame('新しいパスワードは8文字以上で指定してください。', $validator->errors()->first('newPassword'));
    }
}
