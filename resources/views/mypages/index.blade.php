@extends('layouts.app')

@php
$title = 'マイページ画面';
@endphp

@section('title', $title)

@section('content')
{{-- コンテンツを中央寄せしたかったらcontainerのコメントを解除する --}}
<!-- <div class="container"> -->
    <div class="shadow-lg p-3 mb-5 bg-white rounded">{{ $title }}</div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">マイページメニュー</div>

                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item"><a href="{{ route('mypage.user') }}">ユーザー情報編集</a></li>
                        <li class="list-group-item"><a href="#">パスワード編集</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<!-- </div> -->
@endsection