<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\ToolDTO;
use App\DTOs\ToolSocialHandlesDTO;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class ToolImportController extends Controller
{
    public function importForm()
    {
        return view('admin.tools.import', []);
    }

    public function import(Request $request)
    {
        // todo validate $request->home_page_url
        // hydrate the tool create form

        $home_page_url = str($request->home_page_url)
            ->rtrim('/')
            ->toString();

        $toolDto = ToolDTO::fromJson(trim($request->input('tool_json_string')));
        // dd($toolDto);

        // dd($request->input('toolSocialHandlesDTO'));

        $toolSocialHandlesDTO = ToolSocialHandlesDTO::fromJson(
            $request->input('toolSocialHandlesDTO'),
        );

        // dd($toolSocialHandlesDTO);

        $categoryIds = Category::whereIn('name', $toolDto->categories)->get()->map(function ($category) {
            return $category->id;
        });


        session()->flash('success', 'JSON parsed successfully');

        return view('admin.tools.create', [
            'toolDto' => $toolDto,
            'categoryIds' => $categoryIds,
            'home_page_url' => $home_page_url,
            'toolSocialHandlesDTO' => $toolSocialHandlesDTO,
        ]);
    }
}
