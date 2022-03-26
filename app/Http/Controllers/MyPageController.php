<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
     * @TODO フォームリクエストバリデーションを実装する
     * @return RedirectResponse
     */
    public function userEdit(Request $request): View
    {
        // 後にフォームリクエストバリデーションを実装するのでフォーム入力値の確認はここではしないです。
        try {
            User::updateUser($request->user(), $request->get('userName'), $request->get('userEmail'));
        } catch (Exception $e) {
            report($e);
        }

        return view('mypages.user')->with([
            'user' => $request->user(),
            'message' => '編集しました。'
        ]);
    }
}
