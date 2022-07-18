@extends('layouts.app')

@php
$title = 'パスワード編集画面';
@endphp

@section('title', $title)

@section('content')
<div class="container">
    <div class="shadow-lg p-3 mb-5 bg-white rounded">{{ $title }}</div>
    @include('elements.info')
    <form method="POST" action="{{ route('mypage.postPasswordChange') }}">
        @csrf
        <div class="form-group">
            <label for="nowPassword">現在のパスワード</label>
            <input type="password" class="form-control" name="nowPassword" id="nowPassword" required>
            @if($errors->first('nowPassword'))
                <strong class="error" style="color: red">
                    {{ $errors->first('nowPassword') }}<br />
                </strong>
            @endif
            <label for="newPassword">新しいパスワード</label>
            <input type="password" class="form-control" name="newPassword" id="newPassword" required>
            @if($errors->first('newPassword'))
                <strong class="error" style="color: red">
                    {{ $errors->first('newPassword') }}
                </strong>
            @endif
            <label for="newPassword_confirmation">新しいパスワード(確認)</label>
            <input type="password" class="form-control" name="newPassword_confirmation" id="newPassword_confirmation" required>
            @if($errors->first('newPassword_confirmation'))
                <strong class="error" style="color: red">
                    {{ $errors->first('newPassword_confirmation') }}
                </strong>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">変更</button>
    </form>
    <a href="{{ route('mypage') }}">マイページに戻る</a>
</div>
@endsection