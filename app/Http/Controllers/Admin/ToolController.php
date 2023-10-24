<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\ToolDTO;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tool;
use App\Services\ToolServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.tools.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $toolData = [];

        $slug = str($request->name)->slug();

        if ($request->has('uploaded_screenshot')) {
            $toolData['uploaded_screenshot'] = ToolServices::storeScreenShot(
                $request->file('uploaded_screenshot'),
                $slug
            );
        }

        if ($request->has('uploaded_favicon')) {
            $toolData['uploaded_favicon'] = ToolServices::storeFavicon(
                $request->file('uploaded_favicon'),
                $slug
            );
        }

        $tool = DB::transaction(function () use ($request, $slug, $toolData) {
            $insertedTool = Tool::create([
                'name' => $request->name,
                'slug' => $slug,
                'tag_line' => $request->tag_line,
                'pricing_type' => $request->pricing_type,
                'summary' => $request->summary,
                'description' => $request->description,
                'contact_email' => $request->contact_email,
                'domain_name' => $request->domain_name,
                'home_page_url' => $request->home_page_url,
                'top_features' => collect($request->top_features)->filter(function ($value) {
                    return !empty($value);
                }),
                'use_cases' => collect($request->use_cases)->filter(function ($value) {
                    return !empty($value);
                }),
            ] + $toolData);

            // $tool = Tool::find($insertedTool->id);

            $insertedTool->categories()->sync($request->categories);

            return $insertedTool;
        });

        return redirect()->back()->with('success', 'tool created successfully. <a href="' . route('tool.show', ['tool' => $tool->slug]) . '" target="_blank">View</a>');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tool = Tool::find($id);

        $categoryIds = $tool->categories->map(function ($category) {
            return $category->id;
        });

        return view('admin.tools.edit', [
            'tool' => $tool,
            'categoryIds' => $categoryIds,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tool = Tool::find($id);

        $toolData = [];

        $slug = str($request->name)->slug();

        if ($request->has('uploaded_screenshot')) {
            $toolData['uploaded_screenshot'] = ToolServices::storeScreenShot(
                $request->file('uploaded_screenshot'),
                $slug
            );
        }

        if ($request->has('uploaded_favicon')) {
            $toolData['uploaded_favicon'] = ToolServices::storeFavicon(
                $request->file('uploaded_favicon'),
                $slug
            );
        }

        DB::transaction(function () use ($request, $tool, $toolData) {
            $tool->update([
                'name' => $request->name,
                'tag_line' => $request->tag_line,
                'pricing_type' => $request->pricing_type,
                'summary' => $request->summary,
                'description' => $request->description,
                'domain_name' => $request->domain_name,
                'contact_email' => $request->contact_email,
                'home_page_url' => $request->home_page_url,
                'top_features' => collect($request->top_features)->filter(function ($value) {
                    return !empty($value);
                }),
                'use_cases' => collect($request->use_cases)->filter(function ($value) {
                    return !empty($value);
                }),
            ] + $toolData);

            // $tool = Tool::find($insertedTool->id);

            $tool->categories()->sync($request->categories);
        });

        return redirect()->back()->with('success', 'tool updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Tool::find($id)->delete();

        return redirect()->route('admin.tools.index')->with('success', 'deleted successfully');
    }

    public function import(Request $request)
    {
        if ($request->method() == 'POST') {
            // todo validate $request->home_page_url

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

        return view('admin.tools.import');
    }
}
