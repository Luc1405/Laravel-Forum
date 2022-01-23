<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\User;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Post::where([
            ['name', '!=', Null],
            [function ($query) use ($request) {
                if (($term = $request->term)) {
                    $query->orWhere('name', 'LIKE', '%' . $term . '%')->get();
                }
            }]
        ])
            ->orderBy('created_at')
            ->paginate(5);

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
        $this->authorize('posts_create');
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
        $this->authorize('posts_create');

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
        $this->authorize('posts_edit');
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
        $this->authorize('posts_edit');
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
        $this->authorize('posts_delete');
        $post->delete();
        return redirect()->route('posts.index')

            ->with('success','Post deleted!');
    }

    public function liked(Request $request, Post $post){
        $user = User::find(auth()->id());
        $post = Post::find($request->input('id'));
        $post->save();
        $post->user()->attach($user);
        return redirect()->back()->with('status', 'Post has been liked');
    }

    public function unliked(Request $request, Post $post){
        $user = User::find(auth()->id());
        $post = Post::find($request->input('id'));
        $post->save();
        $post->user()->detach($user);
        return redirect()->back()->with('status', 'Post unliked');
    }



    public function updateStatus(Request $request)
    {
        $this->authorize('posts_status');

        $post = Post::findOrFail($request->post_id);
        $post->status = $request->status;
        $post->save();

        return response()->json(['Status changed ok']);
    }
}
