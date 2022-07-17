@extends('layouts.app')

@php
$title = 'ユーザー一覧画面';
@endphp

@section('title', $title)

@section('content')
<div class="shadow-lg p-3 mb-5 bg-white rounded">{{ $title }}</div>

<table class="table">
    <thead>
        <tr>
            <th scope="col">id</th>
            <th scope="col">名前</th>
            <th scope="col">メールアドレス</th>
            <th scope="col">作成日時</th>
            <th scope="col">更新日時</th>
        </tr>
    </thead>
    @foreach($users as $user)
    <tbody>
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->created_at }}</td>
            <td>{{ $user->updated_at }}</td>
        </tr>
    </tbody>
    @endforeach
</table>
<a href="{{ route('mypage') }}">マイページに戻る</a>
@endsection