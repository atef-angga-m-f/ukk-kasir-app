@extends('layouts.app')

@section('title', 'Daftar Penjualan')

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

                    <!-- Page Heading -->
                    <div class="d-flex mb-4 justify-content-between">
                        <h1 class="h3 text-gray-800">Daftar Penjualan</h1>
                        @if (auth()->user()->role == 'admin')
                        <form action="{{ route('export.excel') }}" method="GET" class="form-inline">
                            <select name="range" class="form-control mr-2">
                                <option value="all">Semua</option>
                                <option value="daily">Harian</option>
                                <option value="weekly">Mingguan</option>
                                <option value="monthly">Bulanan</option>
                                <option value="yearly">Tahunan</option>
                            </select>

                            <button type="submit" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-file-export"></i>
                                </span>
                                <span class="text">Export Penjualan (.xlsx)</span>
                            </button>
                        </form>
                    @endif


                        @if (auth()->user()->role == 'cashier')
                        <a href="{{ route('purchase.sale') }}" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Tambah Penjualan</span>
                        </a>
                        @endif
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary"></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pelanggan</th>
                                            <th>Tanggal Penjualan</th>
                                            <th>Total Harga</th>
                                            <th>Dibuat Oleh</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchases as $purchase)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $purchase->member->name ?? 'NON-MEMBER' }}</td>
                                                <td>{{ $purchase->created_at }}</td>
                                                <td>Rp. {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                                                <td>{{ $purchase->kasir->name ?? 'Petugas' }}</td>
                                                <td>
                                                    <button class="btn btn-warning" data-toggle="modal"
                                                        data-target="#detailModal{{ $purchase->id }}">Lihat</button>
                                                    <a href="{{route('purchase.download', $purchase->invoice)}}" class="btn btn-primary">Unduh</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>


                                </table>
                            </div>
                            {{-- Modal Purchase --}}
                            @foreach ($purchases as $purchase)
                                <div class="modal fade" id="detailModal{{ $purchase->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="detailModalLabel{{ $purchase->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Penjualan</h5>
                                                <button type="button" class="btn" data-dismiss="modal"
                                                    aria-label="Tutup">X</button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Member Status:</strong>
                                                    {{ $purchase->member ? 'Member' : 'NON-MEMBER' }}</p>
                                                @if ($purchase->member)
                                                    <p>No. HP: {{ $purchase->member->phone }}</p>
                                                    <p>Poin Member: {{ $purchase->member->point }}</p>
                                                    <p>Bergabung Sejak:
                                                        {{ \Carbon\Carbon::parse($purchase->member->created_at)->format('d F Y') }}
                                                    </p>
                                                @endif

                                                <table class="table table-bordered mt-3">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama Produk</th>
                                                            <th>Qty</th>
                                                            <th>Harga</th>
                                                            <th>Sub Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($purchase->products as $product)
                                                            <tr>
                                                                <td>{{ $product['name'] }}</td>
                                                                <td>{{ $product['quantity'] }}</td>
                                                                <td>Rp.
                                                                    {{ number_format($product['price'], 0, ',', '.') }}
                                                                </td>
                                                                <td>Rp.
                                                                    {{ number_format($product['total'], 0, ',', '.') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                                <div class="text-end mt-3">
                                                    <h4><strong>Total:Rp.
                                                            {{ number_format($purchase->total_amount, 0, ',', '.') }}</strong>
                                                    </h4>
                                                    <p class="mt-3">Dibuat pada:
                                                        {{ $purchase->created_at->format('Y-m-d H:i:s') }}</p>
                                                    <p>Oleh: {{ $purchase->kasir->name ?? 'Petugas' }}</p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary"
                                                    data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
