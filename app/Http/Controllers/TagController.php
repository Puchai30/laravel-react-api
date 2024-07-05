<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Exception;
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * get all tags
     * Get - /api/tags
     */
    public function index()
    {
        try {
            return Tag::paginate(5);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        };
    }

    /**
     * store tags
     * Post - /api/tags/store
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => ['required', Rule::unique('tags', 'slug')],
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
            $tagData = $request->only(['name', 'slug']);
            Tag::create($tagData);

            return response()->json([
                'message' => 'Tag saved successfully',
                'status' => 201
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * update tags
     * Patch - /api/tags/:slug
     */
    public function update($slug)
    {
        try {
            $tag = Tag::where('slug', $slug)->first();

            if (!$tag) {
                return response()->json([
                    'message' => 'Tag not found',
                    'status' => 404
                ], 404);
            }

            $validator = Validator::make(request()->all(), [
                'name' => 'required',
                'slug' => ['required', Rule::unique('tags', 'slug')],
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
            $tag->update(request()->only('name', 'slug'));

            return response()->json([
                'message' => 'tag updated successfully',
                'status' => 201
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * delete single tags with slug
     * Delete - /api/tags/:slug
     */
    public function destory($slug)
    {
        try {
            $tag = Tag::where('slug', $slug)->first();

            if (!$tag) {
                return response()->json([
                    'message' => 'category not found',
                    'status' => 404
                ], 404);
            }
            $tag->delete();
            return response()->json([
                'message' => 'category deleted successfully',

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
