@extends('layouts.app')

@section('content')
<div class="shadow-lg p-3 mb-5 bg-white rounded">支払い入力画面</div>
<payment-form-component route="{{ route('payment.confirm') }}" :categories="{{ $categories }}" :old="{{ json_encode(Session::getOldInput()) }}" :errors="{{ $errors }}"></payment-form-component>
@endsection