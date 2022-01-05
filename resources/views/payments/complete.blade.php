@extends('layouts.app')

@section('title', '支払い入力完了画面')

@section('js')
    <script src="{{ asset('js/common.js') }}" defer></script>
@endsection

@section('content')
    <div class="shadow-lg p-3 mb-5 bg-white rounded">
        @include('elements.alert')

        支払い入力完了画面<br>登録が完了しました。

        <p>
            <button type="button" class="btn btn-primary" onclick="locationHref('{{ route('payment.when') }}');">
                入力画面へ戻る
            </button>
        </p>
    </div>
@endsection
