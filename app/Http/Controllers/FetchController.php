<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;

class FetchController extends Controller
{
    public function fetch($count = null): JsonResponse
    {
        $params = [];
        if ($count !== null) {
            $params['--count'] = $count;
        }

        Artisan::call('google:sync', $params);
        $output = Artisan::output();

        return response()->json(['output' => $output]);
    }
}
