<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('tools')
            ->orderBy('tools_count', 'desc') // Order by tool count in ascending order
            ->get();

        return view('tags.index', [
            'tags' => $tags,
            'pageDataDTO' => new PageDataDTO(
                title: 'All Tags',
                description: 'Browse tools by tags',
                conicalUrl: route('tag.index')
            ),
        ]);
    }

    public function show(Request $request, string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        return view('tags.show', [
            'tag' => $tag,
            'pageDataDTO' => new PageDataDTO(
                title: 'Top ' . $tag->name . ' AI tools',
                description: null,
                conicalUrl: route('tag.show', ['tag' => $tag->slug])
            ),
        ]);
    }
}
