@extends('layouts.app')

@php
    $title = 'カテゴリー一覧画面';
@endphp

@section('title', $title)

@section('content')
    <div class="shadow-lg p-3 mb-5 bg-white rounded">{{ $title }}</div>

    @includeUnless(is_null(session('message')), 'elements.info', ['message' => session('message')])

    @if($categories->isNotEmpty())
        <form method="POST" action="{{ route('category.update') }}">
            @method('PUT')
            @csrf
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">カテゴリー名</th>
                    <th scope="col">
                        <button type="submit" class="btn btn-success">更新</button>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <th scope="row">
                            <label for="categoryName{{ $loop->index }}">{{ $loop->index + 1 }}</label>
                        </th>
                        <td>
                            <input
                                type="text"
                                class="form-control"
                                id="categoryName{{ $loop->index }}"
                                name="categoryName[{{ $category->id }}]"
                                value="{{ $category->name }}"
                                required
                            >
                            @if($errors->get("categoryName.{$category->id}"))
                                <p style="color: red">{{ $errors->first("categoryName.{$category->id}") }}</p>
                            @endif
                        </td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </form>
    @else
        @include('elements.category_empty')
    @endif
@endsection
