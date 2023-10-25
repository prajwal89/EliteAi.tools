<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ToolController extends Controller
{
    public function index()
    {
    }

    public function show(Request $request, string $slug): View
    {
        $tool = Tool::where('slug', $slug)->firstOrFail();

        $relatedTools = Tool::all();

        return view('tools.show', [
            'tool' => $tool,
            'relatedTools' => $relatedTools,
            'pageDataDTO' => new PageDataDTO(
                title: $tool->name . ' - Reviews, Pricing, Use cases, Features',
                description: $tool->summary,
                conicalUrl: route('tool.show', ['tool' => $tool->slug]),
                thumbnailUrl: asset('/tools/' . $tool->slug . '/screenshot.webp')
            )
        ]);
    }

    public function alternatives(Request $request, string $slug): View
    {
        $tool = Tool::where('slug', $slug)->firstOrFail();

        $alternativeTools = Tool::with('categories')->get();

        // $alternativeTools = Tool::whereHas('categories', function ($query) use ($tool) {
        //     $query->whereIn('category_id', $tool->categories->pluck('id'));
        // })
        //     ->where('id', '!=', $tool->id)
        //     ->get();

        return view('tools.alternatives', [
            'tool' => $tool,
            'alternativeTools' => $alternativeTools,
            'pageDataDTO' => new PageDataDTO(
                title: ($alternativeTools->count() - 1) . '+ ' . $tool->name . ' - Alternatives',
                description: 'All Tools alternatives for ' . $tool->name . ' with comparison',
                conicalUrl: route('tool.show', ['tool' => $tool->slug]),
                thumbnailUrl: asset('/tools/' . $tool->slug . '/screenshot.webp')
            )
        ]);
    }
}
