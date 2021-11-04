<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
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
    public function confirm(Request $request): View
    {
        // dd($request->input('payment'));
        return view('payments.confirm')->with('categories', Category::getCategoryAll());
    }

    /**
     * 支払い入力完了画面
     * /payment/complete
     *
     * @return View
     */
    public function complete(): View
    {
        //
    }
}
