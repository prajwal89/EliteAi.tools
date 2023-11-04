<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Models\Blog;
use App\Models\BlogToolSemanticScore;

class BlogController extends Controller
{
    public function show(string $slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        $tools = BlogToolSemanticScore::with('tool')
            ->where('blog_id', $blog->id)
            ->where('score', '>', 0.85)
            ->orderBy('score', 'desc')
            ->get()
            ->map(function ($toolWithScores) {
                $toolWithScores->tool->score = $toolWithScores->score;

                return $toolWithScores->tool;
            });

        // dd($tools);

        return view('blogs.show', [
            'blog' => $blog,
            'tools' => $tools,
            'pageDataDTO' => new PageDataDTO(
                title: $blog->title,
                description: strip_tags($blog->description),
                conicalUrl: route('blog.show', ['blog' => $blog->slug])
            ),
        ]);
    }
}
