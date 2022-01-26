@extends('layouts.app')

@php
    $title = 'カテゴリー一覧画面';
@endphp

@section('title', $title)

@section('content')
    <div class="shadow-lg p-3 mb-5 bg-white rounded">{{ $title }}</div>

@endsection
