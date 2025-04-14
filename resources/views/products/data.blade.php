@extends('layouts.app')

@section('title', 'Daftar Products')

@section('content')
    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('layouts.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                @include('layouts.topbar')

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Page Heading -->
                    <div class="d-flex justify-content-between mb-4">
                        <!-- Page Heading -->
                        <h1 class="h3 text-gray-800">Produk</h1>
                        @if (auth()->user()->role == 'admin')
                            <a href="{{ route('products.create') }}" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Tambah Produk</span>
                            </a>
                        @endif
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Produk</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Gambar</th>
                                            <th>Nama</th>
                                            <th>Harga</th>
                                            <th>Stock</th>
                                            @if (auth()->user()->role == 'admin')
                                             <th></th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><img src="{{ asset('storage/' . $product->img) }}" alt="Product Image"
                                                        width="75"></td>
                                                <td>{{ $product->product_name }}</td>
                                                <td>Rp. {{ number_format($product->price, 0, ',', '.') }}</td>
                                                <td>{{ $product->stock }}</td>
                                                @if (auth()->user()->role == 'admin')
                                                <td>
                                                    <div class="d-flex">

                                                        <a href="{{ route('products.edit', $product->id) }}"
                                                            class="btn btn-warning mr-3 btn-icon-split">
                                                            <span class="icon text-white-50">
                                                                <i class="fas fa-pen"></i>
                                                            </span>
                                                            <span class="text">Edit</span>
                                                        </a>
                                                        <!-- Tombol untuk memicu modal -->
                                                        <a href="#" class="btn btn-primary mr-3 btn-icon-split"
                                                            data-toggle="modal"
                                                            data-target="#updateStockModal{{ $product->id }}">
                                                            <span class="icon text-white-50">
                                                                <i class="fas fa-info"></i>
                                                            </span>
                                                            <span class="text">Update Stock</span>
                                                        </a>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="updateStockModal{{ $product->id }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="updateStockModalLabel{{ $product->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Update Stok Produk</h5>
                                                                        <button class="close" type="button"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form
                                                                            action="{{ route('products.updateStock', $product->id) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('PUT')

                                                                            <div class="mb-3">
                                                                                <label for="productName"
                                                                                    class="form-label">Nama Produk
                                                                                    *</label>
                                                                                <input type="text" class="form-control"
                                                                                    value="{{ $product->product_name }}"
                                                                                    readonly>
                                                                            </div>

                                                                            <div class="mb-3">
                                                                                <label for="stock"
                                                                                    class="form-label">Stok *</label>
                                                                                <input type="number" class="form-control"
                                                                                    name="stock"
                                                                                    value="{{ $product->stock }}"
                                                                                    required>
                                                                            </div>

                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-dismiss="modal">Batal</button>
                                                                                <button type="submit"
                                                                                    class="btn btn-primary">Update</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <form action="{{ route('products.destroy', $product->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-icon-split"
                                                                onclick="return confirm('Yakin ingin menghapus?')">
                                                                <span class="icon text-white-50">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                <span class="text">Hapus</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            @include('layouts.footer')

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>



@endsection
