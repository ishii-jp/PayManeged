<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\MyPageUserEditPostRequest;
use Exception;
use App\Models\User;
use App\Http\Requests\PasswordChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use TypeError;

class MyPageController extends Controller
{
    /**
     *  MyPageController construct
     *
     * Authenticateを設定
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * マイページ画面
     * [GET] /mypage
     *
     * @return View
     */
    public function index(): View
    {
        return view('mypages.index');
    }

    /**
     * ユーザー情報編集画面
     * [GET] /mypage/user
     *
     * @param Request $request
     * @return View
     */
    public function user(Request $request): View
    {
        return view('mypages.user')->with('user', $request->user());
    }

    /**
     * ユーザー情報編集画面
     * [POST] /mypage/user_edit
     *
     * @param MyPageUserEditPostRequest $request
     * @return View
     */
    public function userEdit(MyPageUserEditPostRequest $request): View
    {
        $message = '';
        $validated = $request->validated();

        try {
            User::updateUser($request->user(), Arr::get($validated, 'userName'), Arr::get($validated, 'userEmail'));
            $message = '編集しました。';
        } catch (Exception | TypeError $e) {
            report($e);
            $message = '編集に失敗しました。';
        }

        return view('mypages.user')->with([
            'user' => $request->user(),
            'message' => $message
        ]);
    }

    /**
     * ユーザー一覧画面
     * [GET] /mypage/user_show
     *
     * @return View
     */
    public function userShow(): View
    {
        // TODO 本来はusersテーブルに管理者か一般か判定するフラグを作成しアクセス可否を判定する。
        // 現状判定するカラムがないので、カラム作成後に対応する。
        // abort(404);

        // TODO コントローラで直接all()を呼び出さずモデルに書いてUTを実施するように修正する。
        $users = User::all();

        return view('mypages.user_show')->with('users', $users);
    }

    /**
     * パスワード編集画面
     * [GET] /mypage/password_change
     *
     * @return View
     */
    public function getPasswordChange(Request $request): View
    {
        $message = $request->session()->get('redirectMessage', null);

        if (is_null($message)) {
            return view('mypages.password_change');
        } else {
            return view('mypages.password_change')->with('message', $message);
        }
    }

    /**
     * パスワード編集
     * [POST] /mypage/password_change
     *
     * @param PasswordChangeRequest $request
     * @return RedirectResponse
     */
    public function postPasswordChange(PasswordChangeRequest $request): RedirectResponse
    {
        $redirectMessage = 'パスワードが変更されました。';
        $validated = $request->validated();

        try {
            // TODO 下記の処理はUserモデルに書いてUTを実施する様修正する。
            // パスワードをDBにアップデート
            $user = User::find(Auth::id());
            $user->password = Hash::make(Arr::get($validated, 'newPassword'));
            $user->save();
        } catch (Exception $e) {
            report($e);
            $redirectMessage = 'システムエラーが発生しました。 お手数ですがもう1度最初からお願いします。';
        }

        // メッセージをレスポンスに渡してリダイレクト
        return redirect(route('mypage.getPasswordChange'))->with('redirectMessage', $redirectMessage);
    }
}
