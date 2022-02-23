@extends('layouts.app')

@php
    $title = '支払い履歴グラフ画面';
@endphp

@section('title', $title)

@section('content')
    <div class="shadow-lg p-3 mb-5 bg-white rounded">{{ $title }}</div>
    <a href="{{ route('payment.history') }}">履歴へ戻る</a>
    <payment-chart-component :chartdata="@json($paymentSumList)"></payment-chart-component>
@endsection
