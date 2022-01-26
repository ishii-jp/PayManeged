@extends('layouts.app')

@php
    $title = 'カテゴリー作成画面';
@endphp

@section('title', $title)

@section('content')
    <div class="shadow-lg p-3 mb-5 bg-white rounded">{{ $title }}</div>

    <a href="{{ route('category.show') }}">一覧へ戻る</a>

    @include('elements.info')

    <form method="POST" action="{{ route('category.createPost') }}">
        @csrf
        <div class="form-group">
            <label for="categoryName">カテゴリー名</label>
            <input
                name="categoryName"
                type="text"
                class="form-control"
                id="categoryName"
                aria-describedby="categoryNameHelp"
                placeholder="例：光熱費"
                value="{{ old('categoryName') }}"
                required
            >
            <small id="categoryNameHelp" class="form-text text-muted">
                お好きなカテゴリー名を入力してください。
            </small>
            @if($errors->get('categoryName'))
                <p style="color: red">{{ $errors->first('categoryName') }}</p>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">作成</button>
    </form>
@endsection
