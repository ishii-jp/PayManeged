<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Category;
use App\Services\PaymentService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use TypeError;
use App\Repositories\User\UserRepositoryInterface as User;
use App\Events\InputPaymentCompleted;

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
     * 支払い履歴画面
     * /payment/history
     *
     * @param User $user メソッドインジェクション
     * @return View
     */
    public function history(User $user): View
    {
        return view('payments.history')->with('users', $user->getUserWithPaymentSum(Auth::id()));
    }

    /**
     * 支払い履歴詳細画面
     * /payment/history/detail
     *
     * @param User $user メソッドインジェクション
     * @param string $year 支払い入力する年
     * @param string $month 支払い入力する月
     * @return View
     */
    public function detail(User $user, string $year, string $month): View
    {
        return view('payments.detail')->with('users', $user->getUserWithPayments(Auth::id(), $year, $month));
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
     * @param string $year 支払い入力する年
     * @param string $month 支払い入力する月
     * @return View
     */
    public function index(string $year, string $month): View
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
        } catch (Exception | TypeError) {
            return view('payments.complete')->with('errorMessage', config('message.compError'));
        }

        // 変動費(入力値)と固定費の合計を計算
        $totalAmount = PaymentService::calcPaySum($request->input('paymentSum'), config('const.fixed_cost'));
        // 1人あたりの支払い金額を計算
        $calcResult = PaymentService::calcPayPerPerson($totalAmount);

        /**
         * メール送信
         * 検証用のアドレスが設定されている環境の場合はダミーへ送信する様、第一引数に値を渡しています
         */
        InputPaymentCompleted::dispatch(
            config('mail.dummyAddress') ?? $request->user()->email,
            $request->user()->name,
            $year,
            $month,
            $request->input('payment'),
            $request->input('paymentSum'),
            $totalAmount,
            $calcResult
        );

        return view('payments.complete');
    }
}
