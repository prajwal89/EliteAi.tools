<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TopSearch;
use App\Services\TopSearchService;
use Illuminate\Http\Request;

class TopSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.top-searches.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.top-searches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        TopSearchService::store($request->only('query'));

        return redirect()->back()->with('success', 'Top search created successfully');
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
        return view('admin.top-searches.edit', ['topSearch' => TopSearch::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $isUpdated = TopSearch::find($id)->update($request->only('name', 'description', 'serp_title'));

        return redirect()->back()->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        TopSearch::find($id)->delete();

        return redirect()->route('admin.top-searches.index')->with('success', 'TopSearch deleted successfully');
    }
}
