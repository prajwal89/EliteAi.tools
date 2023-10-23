<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        DB::transaction(function () use ($request, $slug, $toolData) {
            $insertedTool = Tool::create([
                'name' => $request->name,
                'slug' => $slug,
                'tag_line' => $request->tag_line,
                'summary' => $request->summary,
                'domain_name' => $request->domain_name,
                'home_page_url' => $request->home_page_url,
                'top_features' => $request->top_features,
            ] + $toolData);

            // $tool = Tool::find($insertedTool->id);

            $insertedTool->categories()->sync($request->categories);
        });

        return redirect()->back()->with('success', 'tool created successfully');
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
        return view('admin.tools.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
