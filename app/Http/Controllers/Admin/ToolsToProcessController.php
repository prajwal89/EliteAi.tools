<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExtractedToolDomain;
use Illuminate\Http\Request;

class ToolsToProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.tools-to-process.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tools-to-process.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $extractedToolDomain = ExtractedToolDomain::find($id);

        return view('admin.tools-to-process.edit', [
            'extractedToolDomain' => $extractedToolDomain,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        ExtractedToolDomain::find($id)->update([
            'domain_name' => $request->domain_name,
            'home_page_url' => $request->home_page_url,
            'should_process' => $request->has('should_process'),
        ]);

        return redirect()->back()->with('success', 'Domain updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function doNotProcess($id)
    {
        $tool = ExtractedToolDomain::find($id);

        $tool->update([
            'should_process' => 0,
        ]);

        return redirect()->back()->with('success', $tool->domain_name . ' tool will not be processed');
    }
}
