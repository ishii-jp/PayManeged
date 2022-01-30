@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                            
                        <h4>Site menu</h4>
                        <ul class="list-group">
                            <li class="list-group-item"><a href="{{ route('category.create') }}">カテゴリー作成</a></li>
                            <li class="list-group-item"><a href="{{ route('category.show') }}">カテゴリー一覧</a></li>
                            <li class="list-group-item"><a href="{{ route('payment.when') }}">支払い入力</a></li>
                            <li class="list-group-item"><a href="{{ route('payment.history') }}">支払い履歴</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
