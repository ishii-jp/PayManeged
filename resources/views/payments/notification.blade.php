@extends('layouts.app')

@php
    use App\Models\Category;
    use App\Utils\FormatUtil;
    $title = '支払い通知画面';
@endphp

@section('title', $title)

@section('content')
    <div class="shadow-lg p-3 mb-5 bg-white rounded">{{ $title }}</div>
    <a href="{{ route('payment.history') }}">履歴へ戻る</a>

    @if($users->paymentSum->isEmpty() || $users->payments->isEmpty())
        @include('elements.alert', ['errorMessage' => '履歴の取得に失敗しました。'])
    @else
        <div class="alert alert-info" role="alert">
            {{ session('message') ?? '以下の内容にお間違いなければ通知送信ボタンを押してください。' }}
        </div>

        <form action="{{ route('payment.notificationPost', ['year' => $year, 'month' => $month]) }}" method="POST">
            @csrf
            <table class="table">
                @foreach($users->paymentSum as $paymentSum)
                    <tbody>
                    <tr>
                        <th>年</th>
                        <td>{{ $paymentSum->year }}</td>
                    </tr>
                    <tr>
                        <th>月</th>
                        <td>{{ $paymentSum->month }}</td>
                    </tr>
                    @foreach($users->payments as $payment)
                        <tr>
                            <th>{{ Category::getCategoryName($payment->category_id) }}</th>
                            <td>¥{{ FormatUtil::numberFormat($payment->price) }}</td>
                        </tr>
                        <input type="hidden" name="payment[{{ $payment->category_id }}]" value="{{ $payment->price }}">
                    @endforeach
                    <tr>
                        <th>合計金額</th>
                        <td>¥{{ FormatUtil::numberFormat($paymentSum->total_price) }}</td>
                    </tr>
                    <input type="hidden" name="paymentSum" value="{{ $paymentSum->total_price }}">
                    </tbody>
                @endforeach
            </table>

            <div class="form-group">
                <label for="peopleNum">人数</label>
                <input
                    name="peopleNum"
                    type="number"
                    class="form-control"
                    id="peopleNum"
                    aria-describedby="peopleNumHelp"
                    placeholder="0"
                >
                <small id="peopleNumHelp" class="form-text text-muted">
                    支払い金額を割り勘した金額を通知したい場合は、人数を(2 ~ 5まで)数字だけ入力してください。
                </small>
                @if($errors->get('peopleNum'))
                    <p style="color: red">{{ $errors->first('peopleNum') }}</p>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">通知送信</button>
        </form>
    @endif
@endsection
