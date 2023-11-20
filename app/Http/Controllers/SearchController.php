<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('home', [
            'pageDataDTO' => new PageDataDTO(
                title: 'search',
                description: null,
                conicalUrl: route('search.show')
            ),
            'pageType' => 'search',
        ]);
    }
}
