@extends('layouts.app')

@section('content')
<div class="shadow-lg p-3 mb-5 bg-white rounded">支払い入力画面</div>

<form action="{{ route('payment.confirm') }}" method="POST">
    @csrf
    @foreach($categories as $category)
    <div class="mb-3">
        <label for="payment[{{ $category->id }}]" class="form-label">{{ $category->name }}</label>
        <input type="text" class="form-control" id="payment[{{ $category->id }}]" name="payment[{{ $category->id }}]" placeholder="金額を入力してください">
    </div>
    @endforeach
    <button type="submit" class="btn btn-primary">確認</button>
</form>
@endsection