<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use App\Http\Requests\MypagePostRequest;
use Exception;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     * @param MypagePostRequest $request
     * @return RedirectResponse
     */
    public function userEdit(MypagePostRequest $request): View
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
}
