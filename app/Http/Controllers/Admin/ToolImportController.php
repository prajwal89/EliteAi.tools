<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\ToolDTO;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class ToolImportController extends Controller
{
    public function import(Request $request)
    {
        if ($request->method() == 'POST') {
            // todo validate $request->home_page_url
            // hydrate the tool create form

            $home_page_url = str($request->home_page_url)
                ->rtrim('/')
                ->toString();

            $toolDto = ToolDTO::fromJson(trim($request->input('tool_json_string')));

            // dd($toolDto);

            $categoryIds = Category::whereIn('name', $toolDto->categories)->get()->map(function ($category) {
                return $category->id;
            });

            session()->flash('success', 'JSON parsed successfully');

            return view('admin.tools.create', [
                'toolDto' => $toolDto,
                'categoryIds' => $categoryIds,
                'home_page_url' => $home_page_url,
            ]);
        }
    }

    public function importForm()
    {
        $promptForSystem = \App\Services\ExtractedToolProcessor::buildSystemPrompt(public_path('/prompts/prompt.txt'));

        return view('admin.tools.import', [
            'promptForSystem' => $promptForSystem
        ]);
    }
}
