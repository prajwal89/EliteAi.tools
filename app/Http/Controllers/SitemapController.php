<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    public function tools($batchNo = 1)
    {
        $perPage = 5000;

        $offset = ($batchNo - 1) * $perPage;

        $tools = Tool::skip($offset)->take($perPage)->get();

        $xml = $this->generateSitemapXML($tools);

        return Response::make($xml, '200')->header('Content-Type', 'text/xml');
    }

    private function generateSitemapXML($tools)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($tools as $tool) {
            $xml .= '<url>';
            $xml .= '<loc>' . route('tool.show', ['tool' => $tool->slug]) . '</loc>';
            $xml .= '<lastmod>' . $tool->updated_at->tz('UTC')->toAtomString() . '</lastmod>';
            // Add any other necessary XML elements for each tool
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
