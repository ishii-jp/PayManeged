<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * PaymentController construct
     *
     * Authenticateを設定
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 支払い入力画面
     * /payment
     *
     * @return View
     */
    public function index(): View
    {
        return view('payments.index')->with('categories', Category::getCategoryAll());
    }

    /**
     * 支払い入力確認画面
     * /payment/confirm
     *
     * @return View
     */
    public function confirm(PaymentRequest $request): View
    {
        // TODO フォームリクエストバリデーションを実施
        return view('payments.confirm')->with(
            [
                'categories' => Category::getCategoryAll(),
                'payment' => $request->input('payment')
            ]
        );
    }

    /**
     * 支払い入力完了画面
     * /payment/complete
     *
     * @return View
     */
    public function complete(): View
    {
        // TODO DBへインサートを行う

        return view('payments.complete');
    }
}
