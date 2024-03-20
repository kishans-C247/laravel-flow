<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Post;
class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|unique:posts|max:255',
            'content' => 'required',
        ], [
            'title.required' => 'The title field is required.',
            'title.unique' => 'The title has already been taken.',
            'title.max' => 'The title may not be greater than :max characters.',
            'content.required' => 'The content field is required.',
        ]);

        Post::create($validated);
        
        return redirect('/posts')->with('success', 'Post created successfully!');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
{
    $post = Post::findOrFail($id);

    $validated = $request->validate([
        'title' => [
            'required',
            'max:255',
            Rule::unique('posts')->ignore($post->id),
        ],
        'content' => 'required',
    ], [
        'title.required' => 'The title field is required.',
        'title.unique' => 'The title has already been taken.',
        'title.max' => 'The title may not be greater than :max characters.',
        'content.required' => 'The content field is required.',
    ]);

    $post->update($validated);

    return redirect('/posts')->with('success', 'Post updated successfully!');
}

    public function destroy($id)
    {
        Post::findOrFail($id)->delete();
        return redirect('/posts');
    }
}
