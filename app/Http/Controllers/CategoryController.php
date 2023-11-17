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
        $category = Category::where('slug', $slug)->firstOrFail();

        if (!empty($category->serp_title)) {
            $serpTitle = str($category->serp_title)
                ->replace('{count}+', ($category->tools->count() - 1) . '+')
                ->replace('{count}', $category->tools->count());
        } else {
            $serpTitle = 'Top ' . $category->tools->count() . ' ' . $category->name . ' AI Tools';
        }

        return view('home', [
            'pageDataDTO' => new PageDataDTO(
                title: $serpTitle,
                description: 'Browse vast collection of ai tools in ' . $category->name,
                conicalUrl: route('category.show', [
                    'category' => $category->slug,
                ])
            ),
            'pageType' => 'category',
            'category' => $category
        ]);
    }
}
