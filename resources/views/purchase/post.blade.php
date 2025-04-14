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

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <h1 class="h3 mb-4 text-gray-800">Konfirmasi Pembelian</h1>

                    <div class="container mt-4">
                        <div class="card shadow-sm p-4">
                            <div class="row">
                                <!-- Bagian Produk yang Dipilih -->
                                <div class="col-md-6">
                                    <h5 class="fw-bold">Produk yang Dipilih</h5>
                                    <ul class="list-unstyled">
                                        @foreach ($products as $product)
                                            <li class="d-flex justify-content-between">
                                                <div>
                                                    <p class="mb-0">{{ $product['name'] }}</p>
                                                    <small class="text-muted">Rp. {{ number_format($product['price'], 0, ',', '.') }} x {{ $product['quantity'] }}</small>
                                                </div>
                                                <strong>Rp. {{ number_format($product['total'], 0, ',', '.') }}</strong>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <input type="hidden" name="products" value="{{ htmlspecialchars(json_encode($products), ENT_QUOTES, 'UTF-8') }}">

                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <h4><strong>Total</strong></h4>
                                        <h4><strong>Rp. {{ number_format(collect($products)->sum('total'), 0, ',', '.') }}</strong></h4>
                                    </div>
                                </div>

                                <!-- Bagian Form -->
                                <div class="col-md-6">
                                    <form action="{{ route('purchase.sale.store') }}" method="POST">
                                        @csrf
                                        <!-- Simpan JSON Produk di Input Hidden -->
                                        <input type="hidden" name="products" value='@json($products)'>

                                        <div class="mb-2">
                                            <label class="fw-bold">Member Status</label>
                                            <span class="text-danger ms-2">*Dapat juga membuat member</span>
                                            <select class="form-control mt-1" name="member_status" id="member_status">
                                                <option value="non_member">Bukan Member</option>
                                                <option value="member">Member</option>
                                            </select>
                                        </div>

                                        <div class="mb-2 d-none" id="phone_number_field">
                                            <label class="fw-bold">Nomor HP Member</label>
                                            <span class="text-danger ms-2">*( Gunakan / Buat akun member )</span>
                                            <input type="text" class="form-control" name="phone_number" placeholder="08xxxx">
                                        </div>

                                        <div class="mb-2">
                                            <label class="fw-bold">Total Bayar</label>
                                            <input type="text" class="form-control" id="total_payment_display">
                                            <input type="hidden" name="total_payment" id="total_payment">
                                        </div>

                                        <script>
                                            const totalPaymentDisplay = document.getElementById('total_payment_display');
                                            const totalPaymentHidden = document.getElementById('total_payment');

                                            totalPaymentDisplay.addEventListener('input', function (e) {
                                                let rawValue = e.target.value.replace(/[^0-9]/g, '');
                                                totalPaymentHidden.value = rawValue;

                                                if (rawValue) {
                                                    e.target.value = formatRupiah(rawValue);
                                                } else {
                                                    e.target.value = '';
                                                }
                                            });

                                            function formatRupiah(angka) {
                                                let number_string = angka.replace(/[^,\d]/g, '').toString(),
                                                    split   	 = number_string.split(','),
                                                    sisa     	 = split[0].length % 3,
                                                    rupiah     	 = split[0].substr(0, sisa),
                                                    ribuan     	 = split[0].substr(sisa).match(/\d{3}/gi);

                                                if (ribuan) {
                                                    let separator = sisa ? '.' : '';
                                                    rupiah += separator + ribuan.join('.');
                                                }

                                                return 'Rp ' + rupiah + (split[1] !== undefined ? ',' + split[1] : '');
                                            }
                                        </script>



                                        <button type="submit" class="btn btn-primary mt-3">Bayar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.getElementById('member_status').addEventListener('change', function () {
                            const phoneField = document.getElementById('phone_number_field');
                            if (this.value === 'member') {
                                phoneField.classList.remove('d-none');
                            } else {
                                phoneField.classList.add('d-none');
                            }
                        });
                    </script>

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
