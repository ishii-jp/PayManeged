@extends('layouts.app')

@section('title', '支払い入力確認画面')

@section('js')
    <script src="{{ asset('js/common.js') }}" defer></script>
@endsection

@section('content')
    <div class="shadow-lg p-3 mb-5 bg-white rounded">支払い入力確認画面</div>
    <form action="{{ route('payment.complete', ['year' => $year, 'month' => $month]) }}" method="POST">
        @csrf
        @foreach($categories as $category)
            <div class="mb-3">
                <label for="payment[{{ $category->id }}]" class="form-label">{{ $category->name }}</label>
                <p>{{ Arr::get($payment, $category->id) }}</p>
                <input
                    type="hidden"
                    class="form-control-plaintext" id="payment[{{ $category->id }}]"
                    name="payment[{{ $category->id }}]"
                    value="{{ Arr::get($payment, $category->id) }}"
                >
            </div>
        @endforeach
        <label for="paymentSum" class="form-label">合計</label>
        <p>{{ $paymentSum }}</p>
        <input
            type="hidden"
            class="form-control-plaintext"
            id="paymentSum"
            name="paymentSum"
            value="{{ $paymentSum }}"
        >
        {{-- TODO 戻るボタン押下時にフォーム入力値を保持して入力画面へ戻るようにする。 --}}
        <button
            type="button"
            class="btn btn-primary"
            onclick="locationHref('{{ route('payment', ['year' => $year, 'month' => $month]) }}');"
        >
            戻る
        </button>
        <button type="submit" class="btn btn-primary">登録</button>
    </form>
@endsection

