<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'])->only(['store', 'destroy']);
    }


    public function index() {
        $posts = Post::orderBy('created_at', 'desc')->with(['user', 'likes'])->paginate(2);

        return view('posts.index', [
            'posts' => $posts
        ]);
    }

    public function show(Post $post, Request $request) {
        //Should refactor to use Laravel 'Traits' instead
        if($request->user() !== null) {
            $reader = $request->user();
            if (!$post->readBy($request->user()) && !$post->isCreator($reader)) {
                $post->reads()->create([
                    'user_id' => $request->user()->id,
                ]);
            }
        }

        return view('posts.show', [
            'post' => $post,
        ]);
    }

    public function store(Request $request) {
        // dd('ok');
        $this->validate($request, [
            'body' => 'required'
        ]);

        $request->user()->posts()->create([
            'body' => $request->body
        ]);

        return back();
    }

    public function destroy(Post $post) {
        // moved to policy
        // if (!$post->ownedBy(auth()->user())) {
        //     dd('no');
        // }

        $this->authorize('delete', $post); // where 'delete' is the method defined in PostPolicy

        $post->delete();

        return back();
    }
}
