<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:50',
                'icon' => 'nullable|string'
            ]);

            // Generate slug
            $slug = Str::slug($validated['name']);

            // Check if slug exists
            $count = Category::where('slug', 'LIKE', "{$slug}%")->count();
            $validated['slug'] = $count ? "{$slug}-" . ($count + 1) : $slug;

            $category = Category::create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Category created successfully',
                'data' => $category
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:50',
                'icon' => 'nullable|string'
            ]);

            // Update slug if name changes
            if ($validated['name'] !== $category->name) {
                $slug = Str::slug($validated['name']);
                $count = Category::where('slug', 'LIKE', "{$slug}%")
                    ->where('id', '!=', $category->id)
                    ->count();
                $validated['slug'] = $count ? "{$slug}-" . ($count + 1) : $slug;
            }

            $category->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully',
                'data' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update category',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
