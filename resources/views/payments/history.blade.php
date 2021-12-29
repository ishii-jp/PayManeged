@extends('layouts.app')

@section('title', '支払い履歴画面')

@section('content')
<div class="shadow-lg p-3 mb-5 bg-white rounded">支払い履歴画面</div>

@empty($users)
@include('elements.alert', ['errorMessage' => '支払い履歴の取得に失敗しました'])
@else
<table class="table">
    <thead>
        <tr>
            <th scope="col">年</th>
            <th scope="col">月</th>
            <th scope="col">合計金額</th>
            <th scope="col">登録日</th>
        </tr>
    </thead>
    @foreach($users->paymentSum as $paymentSum)
    <tbody>
        <tr>
            <td>{{ $paymentSum->year }}</td>
            <td>{{ $paymentSum->month }}</td>
            <td>{{ $paymentSum->total_price }}</td>
            <td>{{ $paymentSum->updated_at }}</td>
            <td><a href="{{ route('payment.detail', ['year' => $paymentSum->year, 'month' => $paymentSum->month]) }}">詳細</a>
        </tr>
    </tbody>
    @endforeach
</table>
@endempty
@endsection