<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Menu;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $data = [
                'total_penjualan' => Transaction::sum('total_amount'),
                'total_transaksi' => Transaction::count(),
                'total_menu' => Menu::count()
            ];

            return response()->json([
                'status' => true,
                'message' => 'Dashboard data retrieved successfully',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
