<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Videos;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VideosController extends Controller
{
    /**
     * get all videos and filter by category
     * Get - /api/videos
     */
    public function index()
    {
        try {
            return Videos::filter(request(['category', 'tags', 'search']))->paginate(10);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        };
    }

    /**
     * show single videos with slug
     * Get - /api/videos/:slug
     */
    public function show($slug)
    {
        try {
            $video = Videos::where('slug', $slug)->first();

            if (!$video) {
                return response()->json([
                    'message' => 'Video not found',
                    'status' => 404
                ], 404);
            }
            return $video;
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * delete single videos with slug
     * Delete - /api/videos/:slug
     */
    public function destory($slug)
    {
        try {
            $video = Videos::where('slug', $slug)->first();

            if (!$video) {
                return response()->json([
                    'message' => 'Video not found',
                    'status' => 404
                ], 404);
            }
            $video->delete();
            return response()->json([
                'message' => 'video deleted successfully',

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * store videos
     * Post - /api/videos/store
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => ['required', Rule::unique('videos', 'slug')],
            'description' => 'required',
            'photo' => 'required',
            'video' => 'required',
            'category_id' =>  ['required', Rule::exists('categories', 'id')],
            'tags' => 'required|array',
            'tags.*' => ['required', Rule::exists('tags', 'id')],
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors()->messages())->map(function ($messages) {
                return $messages[0];
            });

            return response()->json([
                'error' => $errors,
                'status' => 400
            ], 400);
        }

        try {
            $videoData = $request->only(['title', 'slug', 'description', 'photo', 'video', 'category_id']);
            $video = Videos::create($videoData);
            $video->tags()->attach($request->input('tags'));

            return response()->json([
                'message' => 'Video saved successfully',
                'status' => 201
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * update videos
     * Patch - /api/videos/:slug
     */
    public function update($slug)
    {
        try {
            $video = Videos::where('slug', $slug)->first();

            if (!$video) {
                return response()->json([
                    'message' => 'Video not found',
                    'status' => 404
                ], 404);
            }

            $validator = Validator::make(request()->all(), [
                'title' => 'required',
                'slug' => ['required', Rule::unique('videos', 'slug')],
                'description' => 'required',
                'photo' => 'required',
                'video' => 'required',
                'category_id' =>  ['required', Rule::exists('categories', 'id')],
                'tags' => 'required|array',
                'tags.*' => ['required', Rule::exists('tags', 'id')],

            ]);

            if ($validator->fails()) {
                $errors = collect($validator->errors()->messages())->map(function ($messages) {
                    return $messages[0];
                });

                return response()->json([
                    'error' => $errors,
                    'status' => 400
                ], 400);
            }
            //update data
            $video->update(request()->only('title', 'slug', 'description', 'photo', 'video', 'category_id'));

            // Sync tags
            $video->tags()->sync(request('tags'));

            return response()->json([
                'message' => 'Video updated successfully',
                'status' => 201
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a file in the specified directory and return its public URL.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @return string
     */
    private function storeFile(UploadedFile $file, $directory)
    {
        $filePath = $file->store($directory, 'public');
        return asset('storage/' . $filePath);
    }

    /**
     * upload files
     * Post - /api/videos/upload
     */
    public function upload(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'photo' => ['nullable',  'image'],
            'video' => ['nullable', 'mimes:mp4,mov,avi,flv,mkv', 'max:50480']
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors()->messages())->map(function ($messages) {
                return $messages[0];
            });

            return response()->json([
                'error' => $errors,
                'status' => 400
            ], 400);
        }

        try {

            $data = [];

            if ($request->hasFile('photo')) {
                $data['photo'] = $this->storeFile($request->file('photo'), 'photos');
            }

            if ($request->hasFile('video')) {
                $data['video'] = $this->storeFile($request->file('video'), 'videos');
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
