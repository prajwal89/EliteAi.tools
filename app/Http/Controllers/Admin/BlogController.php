<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\User;
use App\Services\BlogService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.blogs.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $blog = Blog::create([
            'title' => $request->title,
            'serp_title' => $request->serp_title,
            'slug' => str($request->title)->slug(),
            'blog_type' => $request->blog_type,
            'min_semantic_score' => $request->min_semantic_score,
            'user_id' => User::where('email', '00prajwal@gmail.com')->first()->id,
            'description' => $request->description,
        ]);

        BlogService::syncAllEmbeddings($blog);

        return redirect()
            ->route('admin.blogs.edit', ['blog' => $blog->id])
            ->with('success', 'blog created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $blog = Blog::find($id);

        return view('admin.blogs.edit', ['blog' => $blog]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $blog = Blog::find($id);

        $blog->update([
            'title' => $request->title,
            'serp_title' => $request->serp_title,
            'blog_type' => $request->blog_type,
            'min_semantic_score' => $request->min_semantic_score,
            // 'user_id' => User::where('email', '00prajwal@gmail.com')->first()->id,
            'description' => $request->description,
        ]);

        BlogService::syncAllEmbeddings($blog);

        return redirect()
            ->back()
            ->with('success', 'blog updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Blog::find($id)->delete();

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'deleted successfully');
    }
}
