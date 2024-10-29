<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
                'results' => [
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

    public function show($id)
    {
        try {
            $product = Menu::with('category')->where('id', $id)->first();

            if (!$product) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Product retrieved successfully',
                'results' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        Log::info($request->all());
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:category,id',
                'price' => 'required|numeric',
                'image' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $slug = Str::slug($validated['name']);
            $count = Menu::where('slug', 'LIKE', "{$slug}%")->count();
            $validated['slug'] = $count ? "{$slug}-" . ($count + 1) : $slug;

            $category = Category::findOrFail($request->category_id);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $imageName = $validated['name'] . '_' . time() . '.' . $extension;

                $path = $file->storeAs(
                    'product/' . $category->name,
                    $imageName,
                    'public'
                );

                $validated['image'] = $path;
            }

            $product = Menu::create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Product created successfully',
                'results' => $product
            ], 201);

        } catch (ValidationException $e) {
            Log::error('Validation Error: ' . json_encode($e->errors()));
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update($id, Request $request)
    {
        Log::info($request->all());
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'category_id' => 'sometimes|required|exists:category,id',
                'price' => 'sometimes|required|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $product = Menu::findOrFail($id);

            if (isset($validated['name']) && $validated['name'] !== $product->name) {
                $slug = Str::slug($validated['name']);
                $count = Menu::where('slug', 'LIKE', "{$slug}%")
                    ->where('id', '!=', $product->id)
                    ->count();
                $validated['slug'] = $count ? "{$slug}-" . ($count + 1) : $slug;
            }
            $category = Category::where('id', $product->category_id)->first();
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $imagePath = $request->name . '.'. $request->file('image')->extension();
                $path = $request->file('image')->storeAs('menu/' . $category->name, $imagePath, 'public');
                $validated['image'] = $path;
            }

            $product->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully',
                'results' => $product
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation Error: ' . json_encode($e->errors()));
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
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
