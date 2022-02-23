@extends('layouts.app')

@php
    $title = 'カテゴリ別支払い履歴画面';
@endphp

@section('title', $title)

@section('content')
    <div class="shadow-lg p-3 mb-5 bg-white rounded">{{ $title }}</div>
    @if($users->payments->isEmpty())
        @include('elements.alert', ['errorMessage' => '履歴がありません'])
        <p><a href="{{ route('payment.when') }}">こちら</a>から支払い入力をしてください。</p>
    @else
        {{--        <span>カテゴリごとの支払い履歴一覧は下記を選択することで確認できます。</span>--}}
        {{--        <form class="form-inline" action="#">--}}
        {{--            <label class="my-1 mr-2" for="inlineFormCustomSelectPref">カテゴリ</label>--}}
        {{--            <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">--}}
        {{--                <option selected></option>--}}
        {{--                @foreach($users->categories as $category)--}}
        {{--                    <option value="{{ $category->id }}">{{ $category->name }}</option>--}}
        {{--                @endforeach--}}
        {{--            </select>--}}
        {{--            <button type="submit" class="btn btn-primary my-1">選択</button>--}}
        {{--        </form>--}}

        <span>現在年度のグラフ表示は<a href="{{ route('payment.history.graph') }}">こちら</a></span>
        <p>グラフ表示したい年度をクリックすることで、<br>クリックした年のグラフ表示できます。</p>
        <h5>{{  \App\Models\Category::getCategoryName($categoryId, Auth::id()) }}の支払い履歴</h5>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">年</th>
                <th scope="col">月</th>
                <th scope="col">合計金額</th>
                <th scope="col">登録日</th>
            </tr>
            </thead>
            @foreach($users->payments as $payment)
                <tbody>
                <tr>
                    <td>
                        <a href="{{ route('payment.history.graph', ['year' => $payment->year]) }}">
                            {{ $payment->year }}
                        </a>
                    </td>
                    <td>{{ $payment->month }}</td>
                    <td>¥{{ \App\Utils\FormatUtil::numberFormat($payment->price) }}</td>
                    <td>{{ $payment->updated_at }}</td>
                </tr>
                </tbody>
            @endforeach
        </table>
    @endif
@endsection
