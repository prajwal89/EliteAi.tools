<?php

namespace App\Http\Controllers;

use App\DTOs\PageDataDTO;
use App\Models\Tool;

class HomeController extends Controller
{
    public function __invoke()
    {
        $tools = Tool::with(['categories'])->get();

        return view('home', [
            'pageDataDTO' => new PageDataDTO(
                title: 'Home',
                description: null,
                conicalUrl: route('home')
            ),
            'tools' => $tools
        ]);
    }
}
