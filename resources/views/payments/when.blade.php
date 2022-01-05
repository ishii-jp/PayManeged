@extends('layouts.app')

@section('title', '支払い年月選択画面')

@section('content')
    <div class="shadow-lg p-3 mb-5 bg-white rounded">支払い年月選択画面</div>
    <payment-when-component
        years='@json($years)'
        months='@json($months)'
        route="{{ config('env.DOMAIN') }}"
    ></payment-when-component>
@endsection
