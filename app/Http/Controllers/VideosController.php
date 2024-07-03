<?php

namespace App\Http\Controllers;

use App\Models\Videos;
use Exception;
use Illuminate\Http\Request;

/**
 * get all videos and filter by category
 * Get - /api/videos
 * @param category
 */

class VideosController extends Controller
{
    public function index()
    {
        try {
            return Videos::filter(request(['category']))->paginate(5);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        };
    }
}
