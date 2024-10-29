<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::with('products')->get();
        return response()->json([
            'status' => true,
            'message' => 'Success Receive Category',
            'results' => $category
        ]);
    }

    public function show($id)
    {
        $category = Category::where('id', $id)->get();
        return response()->json([
            'status' => true,
            'message' => 'Success Receive Category',
            'results' => $category
        ]);
    }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:50',
            ]);

            $slug = Str::slug($validated['name']);

            $count = Category::where('slug', 'LIKE', "{$slug}%")->count();
            $validated['slug'] = $count ? "{$slug}-" . ($count + 1) : $slug;

            $category = Category::create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Category created successfully',
                'results' => $category
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
                'results' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'status' => true,
                'message' => 'Category deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete category',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
