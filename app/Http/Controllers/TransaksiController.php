<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransaksiController extends Controller
{
    public function index()
    {
        try {
            $transactions = Transaction::all();
            $total_amount = Transaction::where('status', '2')->sum('total_amount');

            return response()->json([
                'status' => true,
                'message' => 'Data retrieved successfully',
                'results' => [
                    'transactions' => $transactions,
                    'total_amount' => $total_amount
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

    public function updateStatus($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $status = $transaction->status == 0 ? "1" : ($transaction->status == 1 ? "2" : $transaction->status);

            $transaction->update([
                "status" => $status
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Transaction status updated successfully',
                'results' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $query = Transaction::query();

            if ($request->filled('nomor_meja')) {
                $query->where('nomor_meja', $request->nomor_meja);
            }

            if ($request->filled('tanggal')) {
                $query->whereDate('tanggal', $request->tanggal);
            }

            $transactions = $query->get();
            $total_amount = $query->where('status', '2')->sum('total_amount');

            return response()->json([
                'status' => true,
                'message' => 'Search results retrieved successfully',
                'results' => [
                    'transactions' => $transactions,
                    'total_amount' => $total_amount
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
