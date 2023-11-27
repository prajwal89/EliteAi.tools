<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PseudoFilesController extends Controller
{
    public function robots()
    {
        echo file_get_contents(asset('robots.txt'));
    }
}
