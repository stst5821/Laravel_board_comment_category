<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;
use App\Http\Requests\PostRequest; // バリデーションのために作った、Http/Request/PostRequest.phpの使用宣言

class PostsController extends Controller
{

    // 投稿一覧

    public function index(Request $request)
    {
        // カテゴリ取得
        $category = new Category;
        $categories = $category->getLists();

        $category_id = $request->category_id;
        
        // 検索ワード取得
        $searchword = $request->searchword;

        // scopeを利用した検索
        $posts = Post::orderBy('created_at', 'desc')
        ->categoryAt($category_id) // ←★これ
        ->fuzzyNameMessage($searchword) // ←★変更
        ->paginate(10);

        return view('bbs.index', [
            'posts' => $posts, 
            'categories' => $categories, 
            'category_id'=>$category_id,
            'searchword' => $searchword // ←★追加
        ]);
    }

    // 投稿詳細

    public function show(Request $request, $id)
{
    $post = Post::findOrFail($id);

    return view('bbs.show', [
        'post' => $post,
    ]);
}

    // 投稿フォーム

    public function create() {
        
        $category = new Category;
        // Category.phpで作った、getLists()メソッドで、$categoryから特定のキーだけ取得。
        // prependメソッドで、作成した配列の先頭に任意の項目（選択というテキスト）を先頭に追加
        $categories = $category->getLists()->prepend('選択', '');
        return view('bbs.create', ['categories' => $categories]);
    }

    public function store(PostRequest $request) {
        $savedata = [
            'name' => $request->name,
            'subject' => $request->subject,
            'message' => $request->message,
            'category_id' => $request->category_id,
        ];

        $post = new Post;
        

        $post->fill($savedata)->save();

        return redirect('/bbs')->with('poststatus', '新規投稿しました');
    }

        /**
     * 編集画面
     */
    public function edit($id)
    {
        $category = new Category;
        $categories = $category->getLists();
        
        $post = Post::findOrFail($id);
        return view('bbs.edit', ['post' => $post, 'categories' => $categories]);
    }
    
    /**
     * 編集実行
     */
    public function update(PostRequest $request)
    {
        $savedata = [
            'name' => $request->name,
            'subject' => $request->subject,
            'message' => $request->message,
            'category_id' => $request->category_id,
        ];
    
        $post = new Post;
        $post->fill($savedata)->save();
    
        return redirect('/bbs')->with('poststatus', '投稿を編集しました');
    }

        /**
     * 物理削除
     */
    public function destroy($id)
    {
    $post = Post::findOrFail($id);
    
    $post->comments()->delete(); // ←★コメント削除実行
    $post->delete();  // ←★投稿削除実行
    
    return redirect('/bbs')->with('poststatus', '投稿を削除しました');
    }
}