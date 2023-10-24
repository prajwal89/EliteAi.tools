<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
    }

    public function show(Request $request, string $slug)
    {

        $categories = Category::has('tools')->get();
        // $categories = Category::withCount(['tools'])->get();

        $category = Category::where('slug', $slug)->firstOrFail();

        return view('home', [
            'categories' => $categories,
            'category' => $category,
            // 'pageDataDTO' => new PageDataDTO(
            //     title: 'Home',
            //     description: null,
            //     conicalUrl: route('home')
            // ),
        ]);
    }
}
