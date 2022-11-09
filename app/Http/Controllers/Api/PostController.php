<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::paginate(10,['name','description','type']);

        return response()->json([
            'status' => true,
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        if($request->hasFile('file'))
        {
            $img=$request->file('file');
            $extension=$img->getClientOriginalExtension();
            $imgName=time().'-'.rand(31,999).'.'.$extension;
            $img->move('storage/',$imgName);
            $destination='storage/'.$imgName;
        }
        else{
            $destination='-';
        }
        $request->file=$destination;

        $post = Post::create($request->all());

        return response()->json([
            'status' => true,
            'message' => "Post Created successfully!",
            'post' => [
                'name'=>$post->name,
                'description'=>$post->description,
                'type'=>$post->type
            ]
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return response()->json([
            'status' => true,
            'post' => [
                'name'=>$post->name,
                'description'=>$post->description,
                'type'=>$post->type,
                'file'=>$post->file
            ]
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(StorePostRequest $request, Post $post)
    {
        if($request->hasFile('file'))
        {
            $imgOld=$post->file;
            if(File::exists($imgOld))
            {
                File::delete($imgOld);
            }
            $img=$request->file('file');
            $extension=$img->getClientOriginalExtension();
            $imgName=time().'-'.rand(31,999).'.'.$extension;
            $img->move('storage/',$imgName);
            $destination='storage/'.$imgName;
        }
        $request->file=$destination;

        $post->update($request->all());

        return response()->json([
            'status' => true,
            'message' => "Post Updated successfully!",
            'post' => $post
        ], 200);
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

        return response()->json([
            'status' => true,
            'message' => "Post Deleted successfully!",
        ], 200);
    }
}
