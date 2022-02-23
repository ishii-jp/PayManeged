@extends('layouts.app')

@php
    $title = 'カテゴリー別支払い履歴グラフ画面';
@endphp

@section('title', $title)

@section('content')
    <div class="shadow-lg p-3 mb-5 bg-white rounded">{{ $title }}</div>
    <a href="{{ route('payment.history.category', ['categoryId' => $categoryId]) }}">カテゴリ別履歴へ戻る</a>
    @unless(empty(\App\Models\Category::getCategoryName($categoryId, Auth::id())))
        <p>{{ \App\Models\Category::getCategoryName($categoryId, Auth::id()) }}のグラフ</p>
    @endunless
    <payment-chart-component :chartdata="@json($paymentsList)"></payment-chart-component>
@endsection
