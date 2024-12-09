<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Z3d0X\FilamentFabricator\Models\Contracts\Page;
use Z3d0X\FilamentFabricator\Facades\FilamentFabricator;

class PageController extends Controller
{
    public function getPageData(?Page $filamentFabricatorPage = null)
    {
        if (blank($filamentFabricatorPage)) {
            $pageUrls = FilamentFabricator::getPageUrls();

            $pageId = array_search('/', $pageUrls);

            $filamentFabricatorPage = FilamentFabricator::getPageModel()::query()
                ->where('id', $pageId)
                ->firstOrFail();
        }

        return response()->json([
            'page' => $filamentFabricatorPage
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
