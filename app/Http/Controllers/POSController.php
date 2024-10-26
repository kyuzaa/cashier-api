<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Log;

class POSController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::with('products')->get();

            return response()->json([
                'status' => true,
                'message' => 'Data retrieved successfully',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $searchTerm = $request->input('query');
            $categories = Category::with(['products' => function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', '%' . $searchTerm . '%');
            }])->get();

            return response()->json([
                'status' => true,
                'message' => 'Search results retrieved successfully',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkout(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array',
                'nomorMeja' => 'required|string'
            ]);

            $cartItems = $request->items;
            $nomorMeja = $request->nomorMeja;

            $totalAmount = collect($cartItems)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            });

            $transaction = Transaction::create([
                'nomor_meja' => $nomorMeja,
                'items' => json_encode($cartItems),
                'total_amount' => $totalAmount,
                'status' => '0',
                'tanggal' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Checkout successful',
                'data' => $transaction
            ], 201);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Checkout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
