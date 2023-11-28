<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'pageDataDTO' => new PageDataDTO(
                title: 'EliteAi tools - Find the one you exactly need',
                description: null,
                conicalUrl: route('home')
            ),
            'pageType' => 'home',
        ]);
    }
}
