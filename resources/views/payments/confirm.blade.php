@extends('layouts.app')

@section('content')
<div class="shadow-lg p-3 mb-5 bg-white rounded">支払い入力確認画面</div>

<form action="{{ route('payment.complete') }}" method="POST">
    @csrf
    @foreach($categories as $category)
    <div class="mb-3">
        <label for="payment[{{ $category->id }}]" class="form-label">{{ $category->name }}</label>
        <input type="text" readonly class="form-control-plaintext" id="payment[{{ $category->id }}]" name="payment[{{ $category->id }}]" value="{{ Arr::get($payment, $category->id) }}">
    </div>
    @endforeach
    {{-- TODO 戻るボタン押下時にフォーム入力値を保持して入力画面へ戻るようにする。 --}}
    <button type="button" class="btn btn-primary" onclick="paymentBack();">戻る</button>
    <button type="submit" class="btn btn-primary">登録</button>
</form>
@endsection

<script>
    function paymentBack() {
        location.href = "{{ route('payment') }}";
    }
</script>
