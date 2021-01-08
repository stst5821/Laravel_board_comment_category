@extends('layout.bbslayout')

@section('title', 'LaravelPjt BBS 投稿の一覧ページ')
@section('keywords', 'キーワード1,キーワード2,キーワード3')
@section('description', '投稿一覧ページの説明文')
@section('pageCss')
<link href="/css/bbs/style.css" rel="stylesheet">
@endsection

@include('layout.bbsheader')

@section('content')
<div class="table-responsive">
    <div class="mt-4 mb-4">
        <a href="{{ route('bbs.create') }}" class="btn btn-primary">
            投稿の新規作成
        </a>
    </div>

    <!-- カテゴリで絞り込みした際の件数を表示している。 -->
    <div class="mt-4 mb-4">
        <p>{{ $posts->total() }}件が見つかりました。</p>
    </div>

    <!-- カテゴリ一覧&カテゴリ毎に記事を絞り込み -->
    <div class="mt-4 mb-4">
        @foreach($categories as $id => $name)
        <span class="btn">
            <a href="{{ route('bbs.index', ['category_id'=>$id]) }}" title="{{ $name }}">
                {{ $name }}
            </a>
        </span>
        @endforeach
    </div>

    <!-- フラッシュメッセージ -->
    @if (session('poststatus'))
    <div class="alert alert-success mt-4 mb-4">
        {{ session('poststatus') }}
    </div>
    @endif

    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>カテゴリ</th>
                <th>作成日時</th>
                <th>名前</th>
                <th>件名</th>
                <th>メッセージ</th>
                <th>処理</th>
            </tr>
        </thead>
        <tbody id="tbl">
            @foreach ($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->category->name }}</td>
                <td>{{ $post->created_at->format('Y.m.d') }}</td>
                <td>{{ $post->name }}</td>
                <td>{{ $post->subject }}</td>
                <td>{!! nl2br(e(Str::limit($post->message, 100))) !!}
                    @if ($post->comments->count() >= 1)
                    <p><span class="badge badge-primary">コメント：{{ $post->comments->count() }}件</span></p>
                    @endif
                </td>
                <td class="text-nowrap">
                    <p>
                        <a href="{{ action('PostsController@show', $post->id) }}" class="btn btn-primary btn-sm">詳細</a>
                    </p>

                    <p>
                        <a href="{{ action('PostsController@edit', $post->id) }}" class="btn btn-info btn-sm">編集</a>
                    </p>

                    <p>
                    <form method="POST" action="{{ action('PostsController@destroy', $post->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">削除</button>
                    </form>
                    </p>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ページネーション追加 -->
    <div class="d-flex justify-content-center mb-5">
        {{ $posts->appends(['category_id' => $category_id])->links() }}
    </div>
    <!-- ページネーションここまで -->

</div>
@endsection

@include('layout.bbsfooter')