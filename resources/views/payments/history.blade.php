@extends('layouts.app')

@section('title', '支払い履歴画面')

@section('content')
    <div class="shadow-lg p-3 mb-5 bg-white rounded">支払い履歴画面</div>
    @if($users->paymentSum->isEmpty())
        @include('elements.alert', ['errorMessage' => '履歴がありません'])
    @else
        <span>現在年度のグラフ表示は<a href="{{ route('payment.history.graph') }}">こちら</a></span>
        <p>グラフ表示したい年度をクリックすることで、<br>クリックした年のグラフ表示できます。</p>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">年</th>
                <th scope="col">月</th>
                <th scope="col">合計金額</th>
                <th scope="col">登録日</th>
                <th scope="col">メニュー</th>
            </tr>
            </thead>
            @foreach($users->paymentSum as $paymentSum)
                <tbody>
                <tr>
                    <td>
                        <a href="{{ route('payment.history.graph') }}?year={{ $paymentSum->year }}">
                            {{ $paymentSum->year }}
                        </a>
                    </td>
                    <td>{{ $paymentSum->month }}</td>
                    <td>¥{{ \App\Utils\FormatUtil::numberFormat($paymentSum->total_price) }}</td>
                    <td>{{ $paymentSum->updated_at }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                メニュー
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a
                                    class="dropdown-item"
                                    href="{{ route('payment.detail', ['year' => $paymentSum->year, 'month' => $paymentSum->month]) }}"
                                >
                                    詳細
                                </a>
                                <a class="dropdown-item" href="#">
                                    {{-- TODO 一旦はメールに送るようにする。のちにLineへ通知など予定 --}}
                                    通知を送信
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            @endforeach
        </table>
    @endif
@endsection
