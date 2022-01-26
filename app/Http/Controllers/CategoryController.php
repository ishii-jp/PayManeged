<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryPutRequest;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Utils\CacheUtil;
use Exception;
use Illuminate\Http\RedirectResponse;
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
     * @return View
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * カテゴリー作成
     * [POST] /category/create
     *
     * @param CategoryRequest $request
     * @return View
     */
    public function createPost(CategoryRequest $request): View
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
     * カテゴリー更新
     * [PUT] /category/update
     *
     * @param CategoryPutRequest $request
     * @return RedirectResponse
     */
    public function update(CategoryPutRequest $request): RedirectResponse
    {
        $userId = Auth::id();
        $validated = $request->validated();
        $message = 'カテゴリーを更新しました。';

        try {
            // カテゴリー名を更新
            Category::updateCategories($userId, Arr::get($validated, 'categoryName'));

            // キャッシュを削除
            CacheUtil::categoryCacheDelete($userId);
        } catch (Exception $e) {
            report($e);
            $message = 'カテゴリーの更新に失敗しました。';
        }

        return redirect(route('category.show'))->with('message', $message);
    }

    /**
     * カテゴリー一覧画面
     * [GET] /category/show
     *
     * @return View
     */
    public function show(): View
    {
        return view('categories.show')->with('categories', Category::getCategoryAll(Auth::id()));
    }
}
