@extends('layouts.app')

@section('title', '支払い履歴詳細画面')

@section('js')
    <script src="{{ asset('js/common.js') }}" defer></script>
@endsection

@section('content')
    <div class="shadow-lg p-3 mb-5 bg-white rounded">支払い履歴詳細画面</div>

    @empty($users)
        @include('elements.alert', ['errorMessage' => '支払い履歴の取得に失敗しました'])
    @else
        @if(count($users->payments) === 0)
            @include('elements.alert', ['errorMessage' => '支払い履歴詳細の取得に失敗しました'])
        @else
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">カテゴリー</th>
                    <th scope="col">金額</th>
                    <th scope="col">登録日</th>
                </tr>
                </thead>
                @foreach($users->payments as $payment)
                    <tbody>
                    <tr>
                        <td>{{ \App\Models\Category::getCategoryName($payment->category_id, Auth::id()) }}</td>
                        <td>¥{{ \App\Utils\FormatUtil::numberFormat($payment->price) }}</td>
                        <td>{{ $payment->updated_at }}</td>
                    </tr>
                    </tbody>
                @endforeach
            </table>
        @endif
    @endempty
    <button type="button" class="btn btn-primary" onclick="locationHref('{{ route('payment.history') }}');">
        履歴へ戻る
    </button>
@endsection
