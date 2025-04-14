<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index(Request $request)
{
    $range = $request->get('range', 'day'); // default harian

    // Tentukan waktu awal berdasarkan range
    switch ($range) {
        case 'week':
            $startDate = now()->subWeek()->startOfDay();
            break;
        case 'month':
            $startDate = now()->subMonth()->startOfDay();
            break;
        default: // 'day'
            $startDate = now()->startOfDay();
            break;
    }

    // Ambil data pembelian berdasarkan waktu
    $sales = DB::table('purchases')
        ->where('created_at', '>=', $startDate)
        ->orderBy('created_at')
        ->get();

    // Kelompokkan berdasarkan tanggal untuk chart penjualan
    $salesPerDay = $sales->groupBy(function ($item) {
        return \Carbon\Carbon::parse($item->created_at)->format('Y-m-d');
    })->map(function ($group) {
        return $group->count();
    });

    $labels = $salesPerDay->keys();
    $totals = $salesPerDay->values();

    // Ambil data produk untuk mendapatkan harga modal
    $productModels = DB::table('products')->select('id', 'cost_price')->get();
    $costMap = $productModels->pluck('cost_price', 'id'); // [id => cost_price]

    $totalProfit = 0;
    $productCount = [];

    foreach ($sales as $purchase) {
        $products = json_decode($purchase->products, true);
        $totalModal = 0;

        foreach ($products as $product) {
            $productId = $product['id'] ?? null;
            $qty = $product['quantity'];
            $name = $product['name'];

            // Hitung total modal pembelian ini
            $cost = $productId !== null ? ($costMap[$productId] ?? 0) : 0;
            $totalModal += $cost * $qty;

            // Hitung total produk terjual
            if (isset($productCount[$name])) {
                $productCount[$name] += $qty;
            } else {
                $productCount[$name] = $qty;
            }
        }

        // Profit per transaksi = total_amount - total_modal
        $totalProfit += $purchase->total_amount - $totalModal;
    }

    return view('dashboard', [
        'labels' => $labels,
        'totals' => $totals,
        'productLabels' => json_encode(array_keys($productCount)),
        'productTotals' => json_encode(array_values($productCount)),
        'totalProfit' => $totalProfit,
        'range' => $range,
        'startDate' => $startDate->format('d M Y'),
        'endDate' => now()->format('d M Y'),
    ]);
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    //
    }
}
