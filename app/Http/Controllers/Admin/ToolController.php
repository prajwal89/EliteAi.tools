<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SearchAbleTable;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Tool;
use App\Models\TopSearch;
use App\Services\MeilisearchService;
use App\Services\ToolService;
use App\Services\TopSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToolController extends Controller
{
    public function index()
    {
        return view('admin.tools.index');
    }

    public function create()
    {
        return view('admin.tools.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $toolData = [];

        $slug = str($request->name)->slug();

        if ($request->has('uploaded_screenshot')) {
            $toolData['uploaded_screenshot'] = ToolService::storeScreenShot(
                $request->file('uploaded_screenshot'),
                $slug
            );
        }

        if ($request->has('uploaded_favicon')) {
            $toolData['uploaded_favicon'] = ToolService::storeFavicon(
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
                'monthly_subscription_starts_from' => $request->monthly_subscription_starts_from,
                'pay_once_price_starts_from' => $request->pay_once_price_starts_from,
                'summary' => $request->summary,
                'description' => $request->description,
                'description_for_nl_processing' => $request->description_for_nl_processing,
                'contact_email' => $request->contact_email,
                'domain_name' => $request->domain_name,
                'home_page_url' => $request->home_page_url,

                'instagram_id' => $request->instagram_id,
                'twitter_id' => $request->twitter_id,
                'tiktok_id' => $request->tiktok_id,
                'linkedin_id' => $request->linkedin_id,
                'linkedin_company_id' => $request->linkedin_company_id,
                'facebook_id' => $request->facebook_id,
                'pinterest_id' => $request->pinterest_id,
                'slack_app_id' => $request->slack_app_id,
                'slack_channel_id' => $request->slack_channel_id,
                'figma_plugin_id' => $request->figma_plugin_id,
                'behance_id' => $request->behance_id,
                'dribbble_id' => $request->dribbble_id,
                'youtube_channel_id' => $request->youtube_channel_id,
                'youtube_handle_id' => $request->youtube_handle_id,
                'subreddit_id' => $request->subreddit_id,
                'discord_channel_invite_id' => $request->discord_channel_invite_id,
                'github_repository_path' => $request->github_repository_path,

                'ios_app_id' => $request->ios_app_id,
                'android_app_id' => $request->android_app_id,
                'window_store_id' => $request->window_store_id,
                'mac_store_id' => $request->mac_store_id,

                'chrome_extension_id' => $request->chrome_extension_id,
                'firefox_extension_id' => $request->firefox_extension_id,

                'has_api' => $request->has('has_api'),

                'yt_introduction_video_id' => $request->yt_introduction_video_id,
                'vimeo_introduction_video_id' => $request->vimeo_introduction_video_id,

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
                        'slug' => str($tag)->trim()->slug(),
                    ], [
                        'name' => trim($tag),
                        'slug' => str($tag)->trim()->slug(),
                    ])->id;
                });

            $insertedTool->tags()->sync($tagIds->toArray()); // Attach the tag to the tool

            $insertedTool->categories()->sync($request->categories);

            // * this is for store only
            collect($request->input('top_searches'))->map(function ($query) use ($insertedTool) {

                $slug = str($query)->trim()->slug()->toString();

                if (empty($slug)) {
                    return;
                }

                if (!TopSearch::where('slug', $slug)->exists()) {
                    TopSearchService::store([
                        'query' => trim($query),
                        'slug' => $slug,
                        'extracted_from_tool_id' => $insertedTool->id,
                    ]);
                }
            });

            return $insertedTool;
        });

        // *download and save favicons
        if ($request->has('should_get_favicon_from_google')) {
            $tool->update(['uploaded_favicon' => ToolService::saveFaviconFromGoogle($tool)]);
        }

        // todo use pipeline
        (new MeilisearchService)->indexDocument(SearchAbleTable::TOOL, $tool->id);

        ToolService::syncAllEmbeddings($tool);

        return redirect()
            ->route('admin.tools.edit', ['tool' => $tool->id])
            ->with('success', 'tool created successfully. <br> <a href="' . route('tool.show', ['tool' => $tool->slug]) . '" target="_blank">View Tool</a>');
    }

    public function show(string $id)
    {
        //
    }

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

    public function update(Request $request, string $id)
    {
        // dd($request->all());

        $tool = Tool::find($id);

        $toolData = [];

        $slug = str($request->name)->slug();

        if ($request->has('uploaded_screenshot')) {
            $toolData['uploaded_screenshot'] = ToolService::storeScreenShot(
                $request->file('uploaded_screenshot'),
                $slug
            );
        }

        if ($request->has('uploaded_favicon')) {
            $toolData['uploaded_favicon'] = ToolService::storeFavicon(
                $request->file('uploaded_favicon'),
                $slug
            );
        }

        DB::transaction(function () use ($request, $tool, $toolData) {

            $tool->update([
                'name' => $request->name,
                'tag_line' => $request->tag_line,
                'pricing_type' => $request->pricing_type,
                'monthly_subscription_starts_from' => $request->monthly_subscription_starts_from,
                'pay_once_price_starts_from' => $request->pay_once_price_starts_from,
                'summary' => $request->summary,
                'description' => $request->description,
                'description_for_nl_processing' => $request->description_for_nl_processing,
                'domain_name' => $request->domain_name,
                'contact_email' => $request->contact_email,
                'home_page_url' => $request->home_page_url,

                'instagram_id' => $request->instagram_id,
                'twitter_id' => $request->twitter_id,
                'tiktok_id' => $request->tiktok_id,
                'linkedin_id' => $request->linkedin_id,
                'linkedin_company_id' => $request->linkedin_company_id,
                'facebook_id' => $request->facebook_id,
                'pinterest_id' => $request->pinterest_id,
                'slack_app_id' => $request->slack_app_id,
                'slack_channel_id' => $request->slack_channel_id,
                'figma_plugin_id' => $request->figma_plugin_id,
                'behance_id' => $request->behance_id,
                'dribbble_id' => $request->dribbble_id,
                'youtube_channel_id' => $request->youtube_channel_id,
                'subreddit_id' => $request->subreddit_id,
                'youtube_handle_id' => $request->youtube_handle_id,
                'discord_channel_invite_id' => $request->discord_channel_invite_id,
                'github_repository_path' => $request->github_repository_path,

                'ios_app_id' => $request->ios_app_id,
                'android_app_id' => $request->android_app_id,
                'window_store_id' => $request->window_store_id,
                'mac_store_id' => $request->mac_store_id,

                'chrome_extension_id' => $request->chrome_extension_id,
                'firefox_extension_id' => $request->firefox_extension_id,

                'has_api' => $request->has('has_api'),

                'yt_introduction_video_id' => $request->yt_introduction_video_id,
                'vimeo_introduction_video_id' => $request->vimeo_introduction_video_id,

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

            $tool->tags()->sync($tagIds->toArray());

            $tool->categories()->sync($request->categories);
        });

        (new MeilisearchService)->indexDocument(SearchAbleTable::TOOL, $tool->id);

        // * these columns are required for calculating vectors so
        //  we need to update again embeddings and distances also
        if ($tool->wasChanged(['name', 'description', 'summary', 'tag_line', 'top_features', 'use_cases'])) {

            $message = ' and also Recalculating vectors and distances';

            ToolService::syncAllEmbeddings($tool);
        }

        return redirect()->back()->with('success', 'tool updated successfully' . @$message);
    }

    public function destroy(string $id)
    {
        Tool::find($id)->delete();

        (new MeilisearchService)->deleteDocument(SearchAbleTable::TOOL, $id);

        return redirect()->route('admin.tools.index')->with('success', 'deleted successfully');
    }
}
