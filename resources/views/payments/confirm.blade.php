@extends('layouts.app')

@section('content')
<div class="shadow-lg p-3 mb-5 bg-white rounded">支払い入力確認画面</div>

<form action="{{ route('payment.complete') }}" method="POST">
    @csrf
    @foreach($categories as $category)
    <div class="mb-3">
        <label for="payment[{{ $category->id }}]" class="form-label">{{ $category->name }}</label>
        <input type="text" class="form-control" id="payment[{{ $category->id }}]" name="payment[{{ $category->id }}]" disabled readonly>
    </div>
    @endforeach
    <button type="submit" class="btn btn-primary">登録</button>
</form>
@endsection