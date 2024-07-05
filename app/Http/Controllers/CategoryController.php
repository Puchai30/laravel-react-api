<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * get all category
     * Get - /api/categories
     */
    public function index()
    {
        try {
            return Category::paginate(5);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        };
    }

    /**
     * store category
     * Post - /api/categories/store
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => ['required', Rule::unique('categories', 'slug')],
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
            $categoryData = $request->only(['name', 'slug']);
            Category::create($categoryData);

            return response()->json([
                'message' => 'Category saved successfully',
                'status' => 201
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * update category
     * Patch - /api/category/:slug
     */
    public function update($slug)
    {
        try {
            $category = Category::where('slug', $slug)->first();

            if (!$category) {
                return response()->json([
                    'message' => 'Category not found',
                    'status' => 404
                ], 404);
            }

            $validator = Validator::make(request()->all(), [
                'name' => 'required',
                'slug' => ['required', Rule::unique('categories', 'slug')],
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
            $category->update(request()->only('name', 'slug'));

            return response()->json([
                'message' => 'category updated successfully',
                'status' => 201
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * delete single category with slug
     * Delete - /api/category/:slug
     */
    public function destory($slug)
    {
        try {
            $category = Category::where('slug', $slug)->first();

            if (!$category) {
                return response()->json([
                    'message' => 'category not found',
                    'status' => 404
                ], 404);
            }
            $category->delete();
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
