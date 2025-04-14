<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProductController extends Controller
{
    /**
     * Fungsi untuk ke halaman utama
     */
    public function data()
    {
        // ambil semua data product
        $products = Product::all();

        // arahkan pengguna dan kirim datanya ke halaman products
        return view('products.data', compact('products'));
    }


    /**
     * Fungsi untuk ke halaman form
     */
    public function create(Request $request)
    {
        // arahkan pengguna ke halaman form
        return view('products.form');
    }

    /**
     * Fungsi untuk ke menyimpan data ke database
     */
    public function store(Request $request)
    {
        // validasi hasil input
        $request->validate([
            'product_name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'img' => 'nullable|mimes:jpeg,png,jpg,gif',
        ]);

        // cek apakah ada gambar yang diunggah
        $imagePath = null;
        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('products', 'public');
        }

        // simpan data ke database
        Product::create([
            'product_name' => $request->product_name,
            'img' => $imagePath,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        // arahkan pengguna ke halaman products
        return redirect()->route('products.data')->with('success', 'Data berhasil ditambahkan');
    }


    /**
     * Fungsi untuk ke halaman form dan mengedit data
     */
    public function edit(Request $request, $id)
    {
        // ambil data product berdasarkan id
        $product = Product::findOrFail($id);

        // arahkan pengguna dan kirim data product ke halaman form
        return view('products.form', compact('product'));
    }

    /**
     * Fungsi untuk ke menyimpan data yang diperbarui ke database
     */
    public function update(Request $request, string $id)
    {
        // ambil data product berdasarkan id
        $product = Product::findOrFail($id);

        // validasi hasil input
        $request->validate([
            'product_name' => 'required',
            'price' => 'required',
            'img' => 'nullable|mimes:jpeg,png,jpg,gif',
        ]);

        // perbarui data produk tanpa mengganti gambar jika tidak ada file baru
        $updateData = [
            'product_name' => $request->product_name,
            'price' => $request->price,
        ];

        // jika ada gambar yang diunggah, simpan dan perbarui path nya
        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('products', 'public');
            $updateData['img'] = $imagePath;
        }

        // perbarui data produk
        $product->update($updateData);

        // arahkan pengguna ke halaman products
        return redirect()->route('products.data')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Fungsi untuk memperbarui stok produk
     */
    public function updateStock(Request $request, string $id)
    {
        // ambil data product berdasarkan id
        $product = Product::findOrFail($id);

        // validasi hasil input
        $request->validate([
            'stock' => 'required',
        ]);

        // perbarui data produk
        $product->update(['stock' => $request->stock]);

        // arahkan pengguna ke halaman products
        return redirect()->route('products.data')->with('success', 'Stok berhasil diperbarui');
    }


    /**
     * Fungsi untuk ke menghapus data
     */
    public function destroy(string $id)
    {
        // ambil data product berdasarkan id lalu hapus
        Product::findOrFail($id)->delete();

        // arahkan pengguna ke halaman products
        return redirect()->route('products.data')->with('success', 'Data berhasil dihapus');
    }
}
