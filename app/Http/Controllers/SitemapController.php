<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [
            [
                'loc' => URL::to('/'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '1.0',
            ],
            [
                'loc' => URL::to('/about'),
                'lastmod' => Carbon::now()->subDays(3)->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => URL::to('/contact'),
                'lastmod' => Carbon::now()->subDays(3)->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => URL::to('/programs'),
                'lastmod' => Carbon::now()->subDay()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => URL::to('/courses'),
                'lastmod' => Carbon::now()->subDay()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ]
        ];

        $xml = view('sitemap', ['urls' => $urls]);

        return Response::make($xml, 200)->header('Content-Type', 'application/xml');
    }
}
