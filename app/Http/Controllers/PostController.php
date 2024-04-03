<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
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
        $post = Post::create($postFields);
        return redirect("/post/{$post->id}")->with('success', 'Post succesfully created!');
    }
    public function viewSinglePost(Post $post)
    {
        $markdownPostBody = strip_tags(Str::markdown($post->body), '<p><ul><ol><li><strong><em><h3><br>');
        $post['body'] = $markdownPostBody;
        return view('single-post', ['post' => $post]);
    }

    public function delete(Post $post)
    {
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post deleted succesfully!');
    }

    public function showEditForm(Post $post)
    {
        return view('edit-post', ['post' => $post]);
    }
    public function updatePost(Post $post, Request $request)
    {
        $postFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $postFields['title'] = strip_tags($postFields['title']);
        $postFields['body'] = strip_tags($postFields['body']);
        $post->update($postFields);
        return back()->with('success', 'Post succesfully updated!');
    }
}
