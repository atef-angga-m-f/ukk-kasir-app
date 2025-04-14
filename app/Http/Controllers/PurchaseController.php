<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function data()
    {
        $purchases = Purchase::with('member')->get();
        return view('purchase.index', compact('purchases'));
    }


    public function sale()
    {
        $products = Product::all();

        return view('purchase.sale', compact('products'));
    }

    public function post(Request $request)
    {
        // Decode JSON dari input
        $productsJson = $request->input('products_json');

        // Pastikan tidak null sebelum decode
        if (empty($productsJson)) {
            return redirect()->back()->with('error', 'Tidak ada produk yang dipilih.');
        }

        $selectedProducts = json_decode($productsJson, true);

        // Pastikan decoding berhasil
        if (!is_array($selectedProducts)) {
            return redirect()->back()->with('error', 'Format data tidak valid.');
        }

        return view('purchase.post', ['products' => $selectedProducts]);
    }



    public function store(Request $request)
    {
        try {
            $request->validate([
                'total_payment' => 'numeric|min:0',
                'products'      => 'json',
            ]);

            $products = json_decode($request->input('products'), true);

            if (!$products) {
                throw new \Exception("Data produk tidak valid.");
            }

            $totalAmount = collect($products)->sum('total');
            $paymentAmount = $request->input('total_payment');
            $returnAmount = $paymentAmount - $totalAmount;

            $member = null;
            $poin = 0;

            // Jika status member
            if ($request->member_status === 'member') {
                $phone = $request->input('phone_number');
                $member = Member::where('phone', $phone)->first();

                $encodedProducts = urlencode(json_encode($products));
                $poin = $member ? $member->point : (int) ($totalAmount * 0.01);

                return redirect()->route('purchase.member.create', [
                    'products'  => $encodedProducts,
                    'total' => $totalAmount,
                    'payment' => $paymentAmount,
                    'poin'  => $poin,
                    'phone' => $phone,
                    'is_registered' => $member ? 'yes' : 'no',
                ]);
            }

            // Bukan member, proses pembelian langsung
            $totalAmountAfterPoin = $totalAmount;

            // Buat invoice unik
            $invoiceNumber = random_int(10000, 99999);
            $idCashier = Auth::user()->id;

            // Simpan transaksi ke database
            $purchase = Purchase::create([
                'invoice' => $invoiceNumber,
                'member_id' => null,
                'kasir_id' => $idCashier,
                'products' => $products,
                'payment_amount' => $paymentAmount,
                'total_amount' => $totalAmountAfterPoin,
                'return_amount' => $returnAmount,
                'discount_amount' => 0,
            ]);

            // Kurangi stok produk berdasarkan quantity yang dibeli
            foreach ($products as $product) {
                $productId = $product['id'];
                $quantity = $product['quantity'];

                $productData = Product::find($productId);
                if ($productData) {
                    $productData->stock -= $quantity;
                    $productData->save();
                }
            }

            return redirect()->route('purchase.detail', $invoiceNumber)->with('success', 'Pesanan berhasil disimpan!');
        } catch (\Throwable $e) {
            return redirect()->route('error.page')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function createMember(Request $request){
        try {
            $products = json_decode(urldecode($request->input('products')), true);
            $total = $request->input('total');
            $payment = $request->input('payment');
            $poin = $request->input('poin');
            $phone = $request->input('phone');

            if (!$products || !$total || !$phone) {
                throw new \Exception("Data yang dikirim tidak lengkap atau tidak valid.");
            }

            // Kurangi stok produk
            foreach ($products as $product) {
                $productId = $product['id'];
                $quantity = $product['quantity'];
                $productData = Product::find($productId);
                if ($productData) {
                    $productData->stock -= $quantity;
                    $productData->save();
                }
            }

            // Cek apakah member sudah terdaftar berdasarkan nomor HP
            $is_registered = Member::where('phone', $phone)->exists() ? 'yes' : 'no';

            $name = "";
            if($is_registered === 'yes'){
                $name = Member::where('phone', $phone)->first()->name;
            }
            return view('purchase.create-member', compact('products', 'total', 'poin', 'phone', 'is_registered', 'name', 'payment'));
        } catch (\Throwable $e) {
            return redirect()->route('error.page')->with('error', 'Gagal membuka form member: ' . $e->getMessage());
        }
    }

    public function purchaseMember(Request $request){
        try {
            $products = json_decode($request->input('products'), true);
            if (!$products) throw new \Exception("Data produk tidak valid.");
            $phone = $request->input('phone');
            $payment = $request->input('payment');
            $totalAmount = $request->input('total');
            $usePoint = $request->has('use_point');
            $isRegistered = $request->input('is_registered');
            if ($isRegistered === 'yes') {
                // Ambil member yang sudah ada
                $member = Member::where('phone', $phone)->firstOrFail();
            } else {
                // Validasi nama hanya kalau member baru
                $request->validate([
                    'name' => 'required',
                ]);

                // Buat member baru
                $member = Member::create([
                    'name' => $request->name,
                    'phone' => $phone,
                    'point' => $request->point
                ]);
            }

            // Hitung potongan poin (1 poin = 1 rupiah misalnya)
            $discount = 0;
            if ($usePoint && $member->point > 0) {
                $discount = min($member->point, $totalAmount);
                $member->point -= $discount;
                $member->save();
            }

            // Hitung total pembayaran
            $totalAmountAfterPoin = $totalAmount - $discount;
            $returnAmount = $payment - $totalAmountAfterPoin;

            $invoiceNumber = random_int(10000, 99999);

            // Simpan pembelian
            Purchase::create([
                'invoice' => $invoiceNumber,
                'member_id' => $member->id,
                'kasir_id' => Auth::user()->id,
                'products' => $products,
                'payment_amount' => $payment,
                'total_amount' => $totalAmountAfterPoin,
                'return_amount' => $returnAmount,
                'discount_amount' => $discount,
            ]);

            return redirect()->route('purchase.detail', $invoiceNumber)
                ->with([
                    'success' => 'Pembelian berhasil disimpan!',
                    'member' => $member,
                    'poin' => $member->point
            ]);
        } catch (\Throwable $e) {
            return redirect()->route('error.page')->with('error', 'Gagal menyimpan pembelian: ' . $e->getMessage());
        }
     }


     public function show($invoiceNumber)
     {

        $purchase = Purchase::where('invoice', $invoiceNumber)->firstOrFail();

        return view('purchase.detail', compact('purchase'));
     }

     public function downloadPdf($invoiceNumber)
     {
        $purchase = Purchase::where('invoice', $invoiceNumber)->firstOrFail();

        // Tambahkan variabel isMember
        $isMember = $purchase->member_id !== null;

        // Kirim ke view
        $pdf = PDF::loadView('purchase.export-pdf', compact('purchase', 'isMember'));
        return $pdf->download('invoice-' . $purchase->invoice . '.pdf');
     }
}
