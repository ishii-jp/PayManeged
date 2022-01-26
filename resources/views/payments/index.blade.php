@extends('layouts.app')

@section('title', '支払い入力画面')

@section('js')
    <script src="{{ asset('js/common.js') }}" defer></script>
@endsection

@section('content')
    <div class="shadow-lg p-3 mb-5 bg-white rounded">支払い入力画面</div>
    {{-- TODO 初期値はDBから、バリデートエラーの場合は入力値を保持するようにする。 --}}
    @if($categories->isNotEmpty())
        <payment-form-component
            route="{{ route('payment.confirm', ['year' => $year, 'month' => $month]) }}"
            :categories="{{ $categories }}"
            :old="{{ json_encode(Session::getOldInput()) }}"
            :errors="{{ $errors }}"
        ></payment-form-component>
        <button type="button" class="btn btn-primary" onclick="locationHref('{{ route('payment.when') }}');">
            年月選択へ戻る
        </button>
    @else
        @include('elements.category_empty')
    @endif
@endsection
