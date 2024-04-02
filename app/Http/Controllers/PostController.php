<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function showCreateForm()
    {
        return view('create-post');
    }

    public function storeNewPost(Request $request)
    {
        $postFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $postFields['title'] = strip_tags($postFields['title']);
        $postFields['body'] = strip_tags($postFields['body']);
        $postFields['user_id'] = auth()->id();
        Post::create($postFields);
    }
}
