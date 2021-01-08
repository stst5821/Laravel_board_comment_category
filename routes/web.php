<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

// 投稿
Route::resource('bbs', 'PostsController', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']]);

// コメント
Route::resource('comment', 'CommentsController', ['only' => ['store']]);