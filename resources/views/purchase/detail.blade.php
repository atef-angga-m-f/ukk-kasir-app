@extends('layouts.app')

@section('title', 'Post')

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

                    <h1 class="h3 mb-4 text-gray-800">Detail Pembayaran</h1>

                    <div class="container mt-4">
                        <div class="card shadow-sm p-4">
                            <!-- Header dengan tombol -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5>Invoice - #{{ $purchase->invoice }}</h5>
                                    <small>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d F Y') }}</small>
                                    <!-- Jika ada data member -->
                                    @if ($purchase->member)
                                    <div class="alert alert-info mb-3">
                                        <strong>Member:</strong> {{ $purchase->member->name }}<br>
                                        <strong>No HP:</strong> {{ $purchase->member->phone }}<br>
                                        <strong>Poin:</strong> {{ $purchase->member->point }}
                                    </div>
                                    @endif

                                </div>
                                <div>
                                    <a href="{{ route('purchase.download', $purchase->invoice) }}" class="btn btn-primary">Unduh</a>
                                    <a href="{{ route('purchase.index') }}" class="btn btn-secondary">Kembali</a>
                                </div>
                            </div>

                            <!-- Tabel Produk -->
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Quantity</th>
                                        <th>Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchase->products as $product)
                                    <tr>
                                        <td>{{ $product['name'] }}</td>
                                        <td>Rp. {{ number_format($product['price'], 0, ',', '.') }}</td>
                                        <td>{{ $product['quantity'] }}</td>
                                        <td>Rp. {{ number_format($product['total'], 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Footer Ringkasan Pembayaran -->
                            <div class="row mt-3">
                                <div class="col-md-9">
                                    <div class="d-flex justify-content-between p-3 bg-light rounded">
                                        <span><strong>POIN DIGUNAKAN</strong> <br> {{ $purchase->discount_amount }}</span>
                                        <span><strong>KASIR</strong> <br> {{ $purchase->kasir->name ?? 'Petugas' }}</span>
                                        <span><strong>JUMLAH BAYAR</strong> <br> Rp. {{ number_format($purchase->payment_amount, 0, ',', '.') }}</span>
                                        <span><strong>KEMBALIAN</strong> <br> Rp. {{ number_format($purchase->return_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-3 bg-dark text-white rounded text-center">
                                        <h6>TOTAL</h6>
                                        @if ($purchase->discount_amount != 0)

                                            <h5 class="text-gray-500" style="text-decoration: line-through">
                                                Rp. {{ number_format($purchase->total_amount + $purchase->discount_amount, 0, ',', '.') }}
                                            </h5>
                                        @endif
                                        <h4><b>Rp. {{ number_format($purchase->total_amount, 0, ',', '.') }}</b> </h4>
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
