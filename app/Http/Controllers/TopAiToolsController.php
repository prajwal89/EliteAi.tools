<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class TopAiToolsController extends Controller
{
    public function show(string $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();

        $tools = $category->tools;

        return view('top-tools', [
            'tools' => $tools,
            'category' => $category,
        ]);
    }
}
