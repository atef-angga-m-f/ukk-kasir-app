<?php

namespace App\Exports;

use App\Models\Purchase;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class PurchasesExport implements FromCollection, WithHeadings
{
    protected $range;

    public function __construct($range = 'all')
    {
        $this->range = $range;
    }

    public function collection()
    {
        $query = Purchase::query();

        // Filter berdasarkan range waktu
        switch ($this->range) {
            case 'daily':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'weekly':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'monthly':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'yearly':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            default:
                break;
        }

        $purchases = $query->get();

        $rows = $purchases->map(function ($purchase) {
            $products = is_string($purchase->products)
                ? json_decode($purchase->products, true)
                : $purchase->products;

            $productNames = collect($products)->map(function ($product) {
                $name = $product['name'];
                $qty = $product['quantity'];
                $price = number_format($product['total'], 0, ',', '.');
                return "{$name} ({$qty} : Rp. {$price})";
            })->join(', ');

            return [
                $purchase->id,
                $purchase->invoice,
                $purchase->member_id ?? '-',
                $purchase->kasir_id,
                $productNames,
                $purchase->total_amount,
                $purchase->payment_amount,
                $purchase->return_amount,
                $purchase->discount_amount,
                $purchase->created_at->format('d-m-Y H:i:s'),
            ];
        });

        // Tambahkan baris total keuntungan di akhir
        $totalProfit = $purchases->sum('total_amount');

        $rows->push([
            '', '', '', '', 'Total Keuntungan',
            '', '', '', '',
            'Rp. ' . number_format($totalProfit, 0, ',', '.'),
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Invoice',
            'Member ID',
            'Kasir ID',
            'Produk',
            'Total Harga',
            'Jumlah Bayar',
            'Kembalian',
            'Diskon',
            'Tanggal',
        ];
    }
}
