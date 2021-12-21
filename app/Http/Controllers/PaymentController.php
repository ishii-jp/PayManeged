<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PaymentRequest;
use App\Models\Category;
use App\Models\Payment;
use App\Models\PaymentSum;
use App\Services\PaymentService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use TypeError;

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
     * 支払い日時選択画面
     * /payment/when
     *
     * @return View
     */
    public function when(): View
    {
        // フォームのセレクトボックスで使う年と月の配列を生成します
        $years = range(PaymentService::getNowYear(), '1990');
        $months = range('1', '12');

        return view('payments.when')->with(['years' => $years, 'months' => $months]);
    }

    /**
     * 支払い入力画面
     * /payment
     *
     * @param Request $request
     * @param string $year 支払い入力する年
     * @param string $month 支払い入力する月
     * @return View
     */
    public function index(Request $request, string $year, string $month): View
    {
        return view('payments.index')->with(
            [
                'categories' => Category::getCategoryAll(),
                'year' => $year,
                'month' => $month
            ]
        );
    }

    /**
     * 支払い入力確認画面
     * /payment/confirm
     *
     * @param PaymentRequest $request
     * @param string $year 支払い入力する年
     * @param string $month 支払い入力する月
     * @return View
     */
    public function confirm(PaymentRequest $request, string $year, string $month): View
    {
        return view('payments.confirm')->with(
            [
                'categories' => Category::getCategoryAll(),
                'payment' => $request->input('payment'),
                'paymentSum' => $request->input('paymentSum'),
                'year' => $year,
                'month' => $month
            ]
        );
    }

    /**
     * 支払い入力完了画面
     * /payment/complete
     *
     * @param PaymentRequest $request
     * @param string $year 支払い入力する年
     * @param string $month 支払い入力する月
     * @return View
     */
    public function complete(PaymentRequest $request, string $year, string $month): View
    {
        try {
            PaymentService::allRegister(
                $year,
                $month,
                Auth::id(),
                $request->input('payment'),
                $request->input('paymentSum')
            );
        } catch (Exception | TypeError $e) {
            return view('payments.complete')->with('errorMessage', config('message.compError'));
        }

        return view('payments.complete');
    }
}
