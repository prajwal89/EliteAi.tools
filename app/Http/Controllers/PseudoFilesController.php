<?php

namespace App\Http\Controllers;

class PseudoFilesController extends Controller
{
    public function robots()
    {
        return response(file_get_contents(asset('robots.txt')))
            ->header('Content-Type', 'text/plain');
    }
}
