<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->paginate(5);
        return view('posts.index',compact('posts'))

            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required',
            'caption' => 'required',
        ]);

        $post = new Post;
        $post->name = $request->input('name');
        $post->image = $request->file('image')->storePublicly('images', 'public');
        $post->image = str_replace('images/', '', $post->image);
        $post->caption = $request->input('caption');

        $post->save();

        return redirect()->route('posts.index') ->with('success','Post made!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('posts.show',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.edit',compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $post = Pokemon::find($post->id);
        if (!$request->input('name') == ""){
            $post->name = $request->input('name');
        }
        if (!$request->file('image') == ""){
            $post->image = $request->file('image')->storePublicly('images', 'public');
            $post->image = str_replace('images/', '', $post->image);
        }
        if (!$request->input('caption') == ""){
            $post->type = $request->input('caption');
        }
        $post->save();
        return redirect()->route('posts.index')
            ->with('success','Post updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')

            ->with('success','Post deleted!');
    }
}
