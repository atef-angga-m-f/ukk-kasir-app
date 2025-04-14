@extends('layouts.app')

@section('title', isset($product) ? 'Edit Product' : 'Tambah Product')

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

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mb-4">
                        <!-- Page Heading -->
                        <h1 class="h3 text-gray-800">{{ isset($product) ? 'Edit Produk' : 'Tambah Produk' }}</h1>

                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-5">
                                        <form
                                            action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}"
                                            method="POST" class="product" enctype="multipart/form-data">
                                            @csrf
                                            @if (isset($product))
                                                @method('PUT')
                                            @endif
                                            <div class="form-group row mb-4">
                                                <div class="col-sm-6">
                                                    <label for="product_name">Nama Produk :</label>
                                                    <input type="text" class="form-control" name="product_name" id="product_name"
                                                        placeholder="Masukkan Nama Produk..." value="{{ isset($product) ? $product->product_name : '' }}" required>
                                                </div>
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <label for="img">Gambar Produk :</label>
                                                    <input type="file" class="form-control" name="img" id="img"
                                                        placeholder="Masukkan Gambar..." value="{{ isset($product) ? $product->img : '' }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <label for="price">Harga Produk :</label>
                                                    <input type="number" class="form-control" name="price" id="price"
                                                    placeholder="Masukkan Harga..." value="{{ isset($product) ? $product->price : '' }}" required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="stock">Stok Produk :</label>
                                                    <input type="number" class="form-control" name="stock" id="stock"
                                                        placeholder="Masukkan Jumlah Stok..." value="{{ isset($product) ? $product->stock : '' }}" {{ isset($product) ? 'disabled' : '' }}>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn mt-5 btn-primary btn-product btn-block">
                                                Simpan
                                            </button>
                                        </form>
                                    </div>
                                </div>
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
