<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('tools')
            ->orderBy('tools_count', 'desc') // Order by tool count in ascending order
            ->get();

        return view('categories.index', [
            'categories' => $categories,
            'pageDataDTO' => new PageDataDTO(
                title: 'All categories',
                description: 'Browse tools by categories',
                conicalUrl: route('category.index')
            ),
        ]);
    }

    public function show(Request $request, string $slug)
    {

        $categories = Category::withCount('tools')
            ->orderBy('tools_count', 'desc') // Order by tool count in ascending order
            ->take(12)
            ->get();

        $category = Category::where('slug', $slug)->firstOrFail();

        return view('home', [
            'categories' => $categories,
            'category' => $category,
            'pageDataDTO' => new PageDataDTO(
                title: 'Top ' . $category->tools->count() . ' ' . $category->name . ' AI Tools',
                description: 'Browse vast collection of ai tools in ' . $category->name,
                conicalUrl: route('category.show', [
                    'category' => $category->slug,
                ])
            ),
        ]);
    }
}
