<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Models\Category;
use App\Models\Tool;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'pageDataDTO' => new PageDataDTO(
                title: 'Home',
                description: null,
                conicalUrl: route('home')
            ),
            'pageType' => 'home'
        ]);
    }
}
