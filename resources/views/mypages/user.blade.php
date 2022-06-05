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
            <label for="name">名前</label>
            <input type="text" class="form-control" name="userName" id="name" value="{{ $user->name }}" required>
            @if($errors->first('userName'))
                <strong class="error" style="color: red">
                    {{ $errors->first('userName') }}<br />
                </strong>
            @endif
            <label for="email">メールアドレス</label>
            <input type="email" class="form-control" name="userEmail" id="email" value="{{ $user->email }}" required>
            @if($errors->first('userEmail'))
                <strong class="error" style="color: red">
                    {{ $errors->first('userEmail') }}
                </strong>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">編集</button>
    </form>
    <a href="{{ route('mypage') }}">マイページに戻る</a>
</div>
@endsection