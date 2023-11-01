<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SearchAbleTable;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Tool;
use App\Services\MeilisearchService;
use App\Services\RecommendationService;
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

                'instagram_id' => $request->instagram_id,
                'twitter_id' => $request->twitter_id,
                'tiktok_id' => $request->tiktok_id,
                'linkedin_id' => $request->linkedin_id,
                'linkedin_company_id' => $request->linkedin_company_id,
                'facebook_id' => $request->facebook_id,
                'youtube_channel_id' => $request->youtube_channel_id,

                'ios_app_id' => $request->ios_app_id,
                'android_app_id' => $request->android_app_id,

                'chrome_extension_id' => $request->chrome_extension_id,
                'firefox_extension_id' => $request->firefox_extension_id,

                'has_api' => $request->has('has_api'),

                'yt_introduction_video_id' => $request->yt_introduction_video_id,

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

        // download and save favicons
        if ($request->has('should_get_favicon_from_google')) {
            $tool->update(
                [
                    'uploaded_favicon' => ToolServices::saveFaviconFromGoogle($tool),
                ]
            );
        }

        MeilisearchService::indexDocument(SearchAbleTable::TOOL, $tool->id);

        ToolServices::updateVectorEmbeddings($tool);

        RecommendationService::saveSemanticDistanceFor($tool);

        return redirect()->route('admin.tools.edit', ['tool' => $tool->id])->with('success', '
        tool created successfully. 
        <br>
        <a href="' . route('tool.show', ['tool' => $tool->slug]) . '" target="_blank">View</a>
        ');
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
        // dd($request->all());

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

                'instagram_id' => $request->instagram_id,
                'twitter_id' => $request->twitter_id,
                'tiktok_id' => $request->tiktok_id,
                'linkedin_id' => $request->linkedin_id,
                'linkedin_company_id' => $request->linkedin_company_id,
                'facebook_id' => $request->facebook_id,
                'youtube_channel_id' => $request->youtube_channel_id,

                'ios_app_id' => $request->ios_app_id,
                'android_app_id' => $request->android_app_id,

                'chrome_extension_id' => $request->chrome_extension_id,
                'firefox_extension_id' => $request->firefox_extension_id,

                'has_api' => $request->has('has_api'),

                'yt_introduction_video_id' => $request->yt_introduction_video_id,

                'top_features' => collect($request->top_features)->filter(function ($value) {
                    return !empty($value);
                }),

                'use_cases' => collect($request->use_cases)->filter(function ($value) {
                    return !empty($value);
                }),
            ] + $toolData);


            $tagIds = str($request->input('tags'))
                ->trim()
                ->trim('.')
                ->explode(',')
                ->map(function ($tag) {
                    return Tag::firstOrCreate([
                        'name' => trim($tag),
                        'slug' => str($tag)->trim()->slug(),
                    ])->id;
                });

            $tool->tags()->sync($tagIds->toArray()); // Attach the tag to the tool

            $tool->categories()->sync($request->categories);
        });

        // todo do this only if data in embedding paragraph changes
        ToolServices::updateVectorEmbeddings($tool);

        RecommendationService::saveSemanticDistanceFor($tool);

        return redirect()->back()->with('success', 'tool updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Tool::find($id)->delete();

        MeilisearchService::deleteDocument(SearchAbleTable::TOOL, 1);

        return redirect()->route('admin.tools.index')->with('success', 'deleted successfully');
    }
}
