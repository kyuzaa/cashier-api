<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        try {
            $products = Menu::orderBy('name', 'ASC')->with('category')->get();
            $categories = Category::all();

            return response()->json([
                'status' => true,
                'message' => 'Data retrieved successfully',
                'data' => [
                    'products' => $products,
                    'categories' => $categories
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:category,id',
                'price' => 'required|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Generate slug
            $slug = Str::slug($validated['name']);
            $count = Menu::where('slug', 'LIKE', "{$slug}%")->count();
            $validated['slug'] = $count ? "{$slug}-" . ($count + 1) : $slug;

            // Handle image upload if exists
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $validated['image'] = $path;
            }

            $product = Menu::create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Product created successfully',
                'data' => $product
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Menu $product)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'category_id' => 'sometimes|required|exists:category,id',
                'price' => 'sometimes|required|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Update slug if name changes
            if (isset($validated['name']) && $validated['name'] !== $product->name) {
                $slug = Str::slug($validated['name']);
                $count = Menu::where('slug', 'LIKE', "{$slug}%")
                    ->where('id', '!=', $product->id)
                    ->count();
                $validated['slug'] = $count ? "{$slug}-" . ($count + 1) : $slug;
            }

            // Handle image upload if exists
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }

                $path = $request->file('image')->store('products', 'public');
                $validated['image'] = $path;
            }

            $product->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully',
                'data' => $product
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Menu::findOrFail($id);
            $product->delete();

            return response()->json([
                'status' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
