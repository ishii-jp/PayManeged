<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Utils\CacheUtil;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * CategoryController construct
     *
     * Authenticateを設定
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * カテゴリー作成画面
     * [GET] /category/create
     *
     */
    public function create(): view
    {
        return view('categories.create');
    }

    /**
     * カテゴリー作成画面
     * [POST] /category/create
     * @todo バリデーションを実装する
     */
    public function createPost(CategoryRequest $request): view
    {
        $validated = $request->validated();
        $userId = Auth::id();
        $message = 'カテゴリーを登録しました。';

        try {
            // カテゴリー名を作成
            Category::createCategoryName($userId, Arr::get($validated, 'categoryName'));

            // キャッシュを削除
            CacheUtil::categoryCacheDelete($userId);
        } catch (Exception $e) {
            report($e);
            $message = '登録に失敗しました。再度お試しください';
        }

        return view('categories.create')->with('message', $message);
    }

    /**
     * カテゴリー一覧画面
     * [GET]/category/show
     *
     */
    public function show()
    {
        //
    }
}
