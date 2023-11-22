<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Enums\BlogType;
use App\Models\Blog;
use App\Models\BlogToolSemanticScore;
use App\Services\BlogService;

class BlogController extends Controller
{
    public function index()
    {
        return view('blogs.index', [
            'allBlogs' => Blog::whereIn('id', BlogService::qualifiedForIndexingBlogIds())->get(),
            'pageDataDTO' => new PageDataDTO(
                title: 'Blog',
                description: null,
                conicalUrl: route('blog.index')
            ),
        ]);
    }

    public function show(string $slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        if ($blog->blog_type == BlogType::SEMANTIC_SCORE) {
            // ! N+1 as we are accessing categories on tool model in views
            $tools = BlogToolSemanticScore::with('tool.categories')
                ->where('blog_id', $blog->id)
                ->where('score', '>', $blog->min_semantic_score)
                ->orderBy('score', 'desc')
                ->get()
                ->map(function ($toolWithScores) {
                    $toolWithScores->tool->score = $toolWithScores->score;

                    return $toolWithScores->tool;
                });
        }

        if (!empty($blog->serp_title)) {
            $serpTitle = str($blog->serp_title)
                ->replace('{count}+', ($tools->count() - 1) . '+')
                ->replace('{count}', $tools->count());
        } else {
            $serpTitle = $blog->title;
        }

        // dd($tools);

        return view('blogs.show', [
            'blog' => $blog,
            'tools' => $tools,
            'pageDataDTO' => new PageDataDTO(
                title: $serpTitle,
                description: strip_tags($blog->description),
                conicalUrl: route('blog.show', ['blog' => $blog->slug])
            ),
        ]);
    }
}
