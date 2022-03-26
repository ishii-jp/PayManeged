@extends('layouts.app')

@php
$title = 'ユーザー情報編集画面';
@endphp

@section('title', $title)

@section('content')
<div class="container">
    <div class="shadow-lg p-3 mb-5 bg-white rounded">{{ $title }}</div>
    @include('elements.info')
    <form method="POST" action="{{ route('mypage.userEdit') }}">
        @csrf
        <div class="form-group">
            <label for="name">メールアドレス</label>
            <input type="text" class="form-control" name="userName" id="name" value="{{ $user->name }}" required>
            <label for="email">メールアドレス</label>
            <input type="email" class="form-control" name="userEmail" id="email" value="{{ $user->email }}" required>
        </div>
        <button type="submit" class="btn btn-primary">編集</button>
    </form>
    <a href="{{ route('mypage') }}">マイページに戻る</a>
</div>
@endsection