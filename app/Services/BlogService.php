<?php

namespace App\Services;

use App\Enums\SearchAbleTable;
use App\Jobs\GenerateFeaturedImageJob;
use App\Jobs\SaveSemanticDistanceBetweenBlogAndToolJob;
use App\Jobs\SaveVectorEmbeddingsJob;
use App\Models\Blog;
use App\Models\BlogToolSemanticScore;
use App\Models\Tool;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class BlogService
{
    public static function saveSemanticDistanceBetweenBlogAndTools(
        Blog $blog,
    ): bool {

        if (empty($blog->_vectors) || count($blog->_vectors) < 1) {
            throw new Exception('Vectors are not calculated for blog: ' . $blog->id);
        }

        $searchResults = MeilisearchService::vectorSearch(
            table: SearchAbleTable::TOOL,
            vectors: $blog->_vectors,
            configs: [
                'limit' => 100,
                'attributesToRetrieve' => ['id', 'name'],
            ],
            retrySettings: [
                'times' => 5,
                'sleepMilliseconds' => 2000,
            ]
        );

        DB::transaction(function () use ($blog, $searchResults) {
            BlogToolSemanticScore::where('blog_id', $blog->id)->delete();

            foreach ($searchResults->hits as $tool) {
                BlogToolSemanticScore::create([
                    'tool_id' => $tool['id'],
                    'blog_id' => $blog->id,
                    'score' => $tool['_semanticScore'],
                    'model_type' => config('custom.current_embedding_model')->value,
                ]);
            }
        });

        return true;
    }

    /**
     *  updates in DB only
     */
    public static function updateVectorEmbeddings(Blog $blog): bool
    {
        $embeddings = (new MeilisearchService)->getVectorEmbeddings(
            $blog->getParagraphForVectorEmbeddings(),
            config('custom.current_embedding_model')
        );

        return $blog->update([
            '_vectors' => $embeddings,
            'model_type' => config('custom.current_embedding_model')->value,
        ]);
    }

    /**
     * all blogs with blog_type = Semantic Score that can be indexed on search engine
     * criteria
     *  min 5 tools required on page
     *  tool should have score > 0.85
     *
     * @return array
     */
    // ! should i cache this query
    public static function qualifiedForIndexingBlogIds()
    {
        return Cache::remember(
            key: 'blog_ids_qualified_for_indexing',
            ttl: now()->addHours(8),
            callback: function () {
                return DB::table('blog_tool_semantic_scores')
                    ->select(['blog_id', DB::raw('count(*) as total_tools')])
                    ->join('tools', 'tools.id', '=', 'blog_tool_semantic_scores.tool_id')
                    ->join('blogs', 'blogs.id', '=', 'blog_tool_semantic_scores.blog_id')
                    // ->where('blog_tool_semantic_scores.score', '>',  config('custom.blog_page.minimum_semantic_score'))
                    ->where('blog_tool_semantic_scores.score', '>', DB::raw('blogs.min_semantic_score'))
                    ->having('total_tools', '>=', config('custom.blog_page.minimum_tools_required'))
                    ->groupBy('blogs.id')
                    ->get()
                    ->pluck('blog_id');
            }
        );
    }

    public static function generateFeaturedImage(Blog $blog)
    {
        $blogAssetPath = public_path('blogs/' . $blog->slug);

        if (!File::isDirectory($blogAssetPath)) {
            File::makeDirectory($blogAssetPath, 0755, true, true);
        }

        $maxIcons = 8;
        $canvasWidth = 1920;
        $canvasHeight = 1080;

        $image = Image::canvas($canvasWidth, $canvasHeight, '#ffffff');

        $tools = BlogToolSemanticScore::with('tool')
            ->where('blog_id', $blog->id)
            ->where('score', '>', $blog->min_semantic_score)
            ->orderBy('score', 'desc')
            ->take($maxIcons)
            ->get();

        $centerX = $canvasWidth / 2;
        $centerY = $canvasHeight / 2;

        $radius = min($canvasWidth, $canvasHeight) / 3; // Adjust the radius as needed

        // Calculate the angle between each icon
        $angleIncrement = 360 / $tools->count();

        $tools->each(function ($tool, $i) use ($image, $centerX, $centerY, $radius, $angleIncrement) {
            // Calculate the position on the circumference
            $angle = $i * $angleIncrement;
            $x = round($centerX + $radius * cos(deg2rad($angle)));
            $y = round($centerY + $radius * sin(deg2rad($angle)));

            $icon = Image::make('http://clgnotes.esy.es/public/tools/' . $tool->tool->slug . '/favicon.webp')
                ->resize(160, 160);

            $image->insert(
                $icon,
                'top-left',
                $x - 100, // Adjust the icon position to center it properly
                $y - 100
            );
        });

        $image->blur(10);

        $image->rectangle(0, 0, $canvasWidth, $canvasHeight, function ($draw) {
            $draw->background('rgba(255, 255, 255, 0.5)');
        });

        $image->text($blog->title, $centerX, $centerY, function ($font) {
            // $font->file(public_path('fonts/ShortBaby-Mg2w.ttf'));
            $font->file(public_path('fonts/ChrustyRock-ORLA.ttf'));
            $font->size(120); // Adjust the font size as needed
            $font->color('#000000');
            $font->align('center');
            $font->valign('middle');
        });

        $sizes = [
            ['width' => 1920, 'height' => 1080, 'suffix' => 'featured'],
            ['width' => 600, 'height' => 400, 'suffix' => 'featured-large'],
            ['width' => 400, 'height' => 400, 'suffix' => 'featured-medium'],
            ['width' => 100, 'height' => 400, 'suffix' => 'featured-small'],
        ];

        foreach ($sizes as $size) {
            $image->resize($size['width'], $size['height'], function ($constraint) {
                $constraint->aspectRatio();
            })->save($blogAssetPath . '/' . $size['suffix'] . '.webp');
        }
    }

    public static function syncAllEmbeddings(Blog $blog): bool
    {
        dispatch(new SaveVectorEmbeddingsJob($blog));

        dispatch(new SaveSemanticDistanceBetweenBlogAndToolJob($blog))->delay(300);

        dispatch(new GenerateFeaturedImageJob($blog))->delay(600);

        return true;
    }
}
