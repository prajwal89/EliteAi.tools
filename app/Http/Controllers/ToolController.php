<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Models\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index()
    {
    }

    public function show(Request $request, string $slug)
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
}
