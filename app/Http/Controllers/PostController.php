<?php

namespace App\Http\Controllers;

use App\Model\Post;
use App\Models\Post as ModelsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    //membuat method 
    public function index()
    {
        //get posts
        $posts = ModelsPost::latest()->paginate(5);
        //render view with posts
        return view('posts.index', compact('posts'));
    }

    // membuat method created
    public function create()
    {
        return view('posts.create');
    }

    // membuat method store
    public function store(Request $request)
    {
        //validasi form 
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);

        // proses upload images
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());


        //proses create posts
        ModelsPost::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'content' => $request->content
        ]);

        //redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Disimpan']);
    }


    //mebuat method edit
    public function edit(ModelsPost $post)
    {
        return view('posts.edit', compact('post'));
    }

    //proses update data
    public function update(Request $request, ModelsPost $post)
    {
        //validate form
        $this->validate($request, [
            'image'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required|min:5',
            'content'   => 'required|min:10'
        ]);

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            //delete old image
            Storage::delete('public/posts/'.$post->image);

            //update post with new image
            $post->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content
            ]);

        } else {

            //update post without image
            $post->update([
                'title'     => $request->title,
                'content'   => $request->content
            ]);
        }

        //redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Diubah!']);
    }


    //method atau fungsi hapus (destroy)
    public function destroy(ModelsPost $post)
    {
        //delete image
        Storage::delete('public/posts/'. $post->image);

        //delete post
        $post->delete();

        //redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }


}
