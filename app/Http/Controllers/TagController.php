<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('tools')
            ->orderBy('tools_count', 'desc')
            ->when(!isAdmin(), function ($query) {
                // show all tags to admin
                $query->having('tools_count', '>=', config('custom.tag_page.minimum_tools_required'));
            })
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

    public function show(string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        abort_if(isAdmin() ? false : $tag->tools()->count() < config('custom.tag_page.minimum_tools_required'), 404);

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
