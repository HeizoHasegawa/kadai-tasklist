@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <h3>{{ Auth::user()->name }}さんのタスク一覧</h3>
        {{-- タスク一覧 --}}
        @include("tasks.tasks")
    @else
        <div class="center jumbotron">
            <div class="text-center">
                <h1>Welcome to the Tasklists</h1>
                {{-- ユーザ登録ページへのリンク --}}
                {!! link_to_route('signup.get', '新規登録', [], ['class' => 'btn btn-lg btn-primary']) !!}
            </div>
        </div>
    @endif
@endsection