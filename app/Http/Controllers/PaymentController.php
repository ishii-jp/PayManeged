<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Category;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
     * グラフ画面のみクエリパラメーターをバリデートします
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('paramYearValid')->only(['graph']);
    }

    /**
     * 支払い履歴画面
     * [GET] /payment/history
     *
     * @param User $user メソッドインジェクション
     * @return View
     */
    public function history(User $user): View
    {
        return view('payments.history')
            ->with('users', $user->getUserWithPaymentSum(Auth::id(), '', true));
    }

    /**
     * カテゴリ別支払い履歴画面
     * [GET] /payment/history/category/{categoryId}
     *
     * @param User $user メソッドインジェクション
     * @param string $categoryId カテゴリーID
     * @return View
     */
    public function historyByCategory(User $user, string $categoryId): View
    {
        return view('payments.history_category')
            ->with([
                'users' => $user->getWithPaymentsByCategoryId(Auth::id(), $categoryId),
                'categoryId' => $categoryId
            ]);
    }

    /**
     * 支払い履歴グラフ画面
     * [GET] /payment/history/graph
     *
     * @param Request $request
     * @param User $user
     * @return View
     */
    public function graph(Request $request, User $user): View
    {
        $reqUsers = $user->getUserWithPaymentSum(
            Auth::id(),
            $request->query('year') ?? PaymentService::getNowYear()
        );

        return view('payments.history_graph')
            ->with('paymentSumList', PaymentService::getMonthlyPaymentSum($reqUsers->paymentSum));
    }

    /**
     * カテゴリ別支払い履歴グラフ画面
     * [GET] /payment/history/graph/category
     *
     * @param Request $request
     * @param User $user
     * @return View
     */
    public function graphByCategory(Request $request, User $user): View
    {
        $categoryId = $request->query('categoryId');

        // カテゴリーID未設定のリクエストはエラーにします。
        abort_if(is_null($categoryId), 500);

        $reqUsers = $user->getWithPaymentsByCategoryId(
            Auth::id(),
            $categoryId,
            $request->query('year') ?? PaymentService::getNowYear()
        );

        return view('payments.history_graph_category')->with([
            'paymentsList' => PaymentService::getMonthlyPaymentSum($reqUsers->payments, true),
            'categoryId' => $categoryId
        ]);
    }

    /**
     * 支払い履歴詳細画面
     * [GET] /payment/history/detail
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
     * 支払い通知画面
     * [GET] /payment/notification
     *
     * @param User $user メソッドインジェクション
     * @param string $year 通知する年
     * @param string $month 通知する月
     * @return View
     */
    public function notification(User $user, string $year, string $month): View
    {
        return view('payments.notification')->with([
            'users' => $user->getUserWithPaymentsAndSum(Auth::id(), $year, $month),
            'year' => $year,
            'month' => $month
        ]);
    }

    /**
     * 支払い通知画面
     * [POST] /payment/notification
     *
     * @param PaymentRequest $request
     * @param string $year
     * @param string $month
     * @return RedirectResponse
     */
    public function notificationPost(PaymentRequest $request, string $year, string $month): RedirectResponse
    {
        $message = '通知を送信しました。';
        $validated = $request->validated();

        try {
            // メール送信
            PaymentService::sendNotification(
                $request->user()->name,
                config('mail.dummyAddress') ?? $request->user()->email,
                $year,
                $month,
                Arr::get($validated, 'paymentSum'),
                Arr::get($validated, 'payment'),
                Arr::get($validated, 'numOfPeople')
            );
        } catch (Exception | TypeError $e) {
            report($e);
            $message = '予期せぬ不具合が発生しました。通知が送信されていない場合があります。';
        }

        return redirect(route('payment.notification', ['year' => $year, 'month' => $month]))
            ->with('message', $message);
    }

    /**
     * 支払い日時選択画面
     * [GET] /payment/when
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
     * [GET] /payment
     *
     * @param string $year 支払い入力する年
     * @param string $month 支払い入力する月
     * @return View
     */
    public function index(string $year, string $month): View
    {
        return view('payments.index')->with(
            [
                'categories' => Category::getCategoryAll(Auth::id()),
                'year' => $year,
                'month' => $month
            ]
        );
    }

    /**
     * 支払い入力確認画面
     * [POST] /payment/confirm
     *
     * @param PaymentRequest $request
     * @param string $year 支払い入力する年
     * @param string $month 支払い入力する月
     * @return View
     */
    public function confirm(PaymentRequest $request, string $year, string $month): View
    {
        $validated = $request->validated();

        return view('payments.confirm')->with(
            [
                'categories' => Category::getCategoryAll(Auth::id()),
                'payment' => Arr::get($validated, 'payment'),
                'paymentSum' => Arr::get($validated, 'paymentSum'),
                'year' => $year,
                'month' => $month
            ]
        );
    }

    /**
     * 支払い入力完了画面
     * [POST] /payment/complete
     *
     * @param PaymentRequest $request
     * @param string $year 支払い入力する年
     * @param string $month 支払い入力する月
     * @return View
     */
    public function complete(PaymentRequest $request, string $year, string $month): View
    {
        $validated = $request->validated();
        $payments = Arr::get($validated, 'payment');
        $paymentSum = Arr::get($validated, 'paymentSum');

        try {
            PaymentService::allRegister(
                $year,
                $month,
                Auth::id(),
                $payments,
                $paymentSum
            );
        } catch (Exception | TypeError $e) {
            report($e);
            return view('payments.complete')->with('errorMessage', config('message.compError'));
        }

        // 変動費(入力値)と固定費の合計を計算
        $totalAmount = PaymentService::calcPaySum($paymentSum, config('const.fixed_cost'));

        /**
         * メール送信
         * 検証用のアドレスが設定されている環境の場合はダミーへ送信する様、第一引数に値を渡しています
         */
        InputPaymentCompleted::dispatch(
            config('mail.dummyAddress') ?? $request->user()->email,
            $request->user()->name,
            $year,
            $month,
            $payments,
            $paymentSum,
            $totalAmount,
            PaymentService::calcPayPerPerson($totalAmount) // 1人あたりの支払い金額を計算
        );

        return view('payments.complete');
    }
}
