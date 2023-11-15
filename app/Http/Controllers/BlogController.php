<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Enums\BlogType;
use App\Models\Blog;
use App\Models\BlogToolSemanticScore;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function index()
    {
        // Blogs what matches criteria for indexing on google
        $qualifiedBlogs = DB::table('blog_tool_semantic_scores')
            ->select(['blog_id', DB::raw('count(*) as total_tools')])
            ->join('tools', 'tools.id', '=', 'blog_tool_semantic_scores.tool_id')
            ->join('blogs', 'blogs.id', '=', 'blog_tool_semantic_scores.blog_id')
            ->where('blog_tool_semantic_scores.score', '>', 0.850)
            ->having('total_tools', '>', 3)
            ->groupBy('blogs.id')
            ->get()
            ->pluck('blog_id');

        $allBlogs = Blog::whereIn('id', $qualifiedBlogs)->get();

        return view('blogs.index', [
            'allBlogs' => $allBlogs,
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
