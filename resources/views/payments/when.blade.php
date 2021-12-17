@extends('layouts.app')

@section('title', '支払い年月選択画面')

@section('content')
    <div class="shadow-lg p-3 mb-5 bg-white rounded">支払い年月選択画面</div>
    <when-form-component years='@json($years)' months='@json($months)' route="{{ config('env.DOMAIN') }}"></when-form-component>
@endsection
