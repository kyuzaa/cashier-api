<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
<<<<<<< HEAD
    public function show()
    {
        $category = Category::all();
        return response()->json([
            'status' => true,
            'message' => 'Success Receive Category',
            'results' => $category
        ]);
    }
=======
>>>>>>> 6decea440c456a0f635695f0c2f9c5e9d191b254
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:50',
<<<<<<< HEAD
=======
                'icon' => 'nullable|string'
>>>>>>> 6decea440c456a0f635695f0c2f9c5e9d191b254
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
<<<<<<< HEAD
                'results' => $category
=======
                'data' => $category
>>>>>>> 6decea440c456a0f635695f0c2f9c5e9d191b254
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
<<<<<<< HEAD
                'results' => $category
=======
                'data' => $category
>>>>>>> 6decea440c456a0f635695f0c2f9c5e9d191b254
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
