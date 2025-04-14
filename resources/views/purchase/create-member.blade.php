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
                    <h1 class="h3 mb-4 text-gray-800">
                        {{ $is_registered === 'yes' ? 'Gunakan Poin Member' : 'Buat Member Baru' }}
                    </h1>

                    <div class="container mx-auto px-4 py-6 flex gap-6">
                        {{-- Kiri: Tabel Produk --}}
                        <div class="w-full bg-white p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4">Detail Pembelian</h3>
                            <div class="table-responsive">

                                <table class="table w-full border text-sm">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="py-2 px-3 border">Nama Produk
                                            </th>
                                            <th class="py-2 px-3 border">Quantity</th>
                                            <th class="py-2 px-3 border">Harga</th>
                                            <th class="py-2 px-3 border">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td class="py-2 px-3 border">
                                                    {{ $product['name'] }}</td>
                                                <td class="py-2 px-3 border">
                                                    {{ $product['quantity'] }}</td>
                                                <td class="py-2 px-3 border">Rp.

                                                    {{ number_format($product['price'], 0, ',', '.') }}</td>
                                                <td class="py-2 px-3 border">Rp.

                                                    {{ number_format($product['total'], 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 text-right space-y-1">
                                <p>Total Harga :
                                    <strong> <span class="text-lg">Rp.
                                            {{ number_format($total, 0, ',', '.') }}
                                        </span>
                                    </strong>
                                </p>
                                <p>Total Bayar :
                                    <strong> <span class="text-lg">Rp.
                                            {{ number_format($payment, 0, ',', '.') }}
                                        </span>
                                    </strong>
                                </p>
                            </div>
                        </div>

                        {{-- Kanan: Form Member --}}
                        <div class="w-full mt-3 mb-5 bg-white p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4">Identitas Member</h3>
                            <form action="{{ route('purchase.member.store') }}" method="POST" class="space-y-4">
                                @csrf

                                <div class="d-flex justify-content-between">


                                            <div>
                                                <div class="mb-2" id="phone_number_field">
                                                    <label class="fw-bold" for="name">Nama : </label>
                                                    <input type="text" id="name" name="name"
                                                        value="{{ $is_registered == 'yes' ? $name : '' }}"
                                                        placeholder="Nama lengkap" required
                                                        {{ $is_registered == 'yes' ? 'disabled' : '' }}
                                                        class="w-full border px-3 py-2 rounded">
                                                </div>

                                                <div class="mb-2">
                                                    <label class="fw-bold">No. HP : </label>
                                                    <input type="number" name="phone" id="phone"
                                                        value="{{ $phone }}" disabled
                                                        class="w-full border px-3 py-2 rounded">
                                                </div>
                                            </div>


                                        {{-- Poin --}}
                                        <div>
                                            <label class="block text-sm mb-1">Poin Saat
                                                Ini</label>
                                            <input type="text" name="point" value="{{ $poin }}" readonly
                                                class="w-full border px-3 py-2 rounded bg-gray-100 text-gray-700" />
                                            {{-- Checkbox gunakan poin (hanya aktif jika member lama) --}}
                                            <div class="flex items-center space-x-2 text-sm text-gray-700">
                                                <input type="checkbox" name="use_point" value="1"
                                                    {{ $is_registered === 'yes' ? '' : 'disabled' }} />
                                                <label>Gunakan poin untuk
                                                    potongan harga</label>
                                            </div>

                                            @if ($is_registered === 'no')
                                                <p class="text-sm text-danger">*Poin tidak dapat digunakan pada pembelanjaan
                                                    pertama.
                                                </p>
                                            @endif
                                        </div>




                                    {{-- Hidden input kirim produk dan total --}}
                                    <input type="hidden" name="products" value="{{ json_encode($products) }}">
                                    <input type="hidden" name="total" value="{{ $total }}">
                                    <input type="hidden" name="phone" value="{{ $phone }}">
                                    <input type="hidden" name="payment" value="{{ $payment }}">
                                    <input type="hidden" name="is_registered" value="{{ $is_registered }}">

                                    {{-- Submit --}}
                                    <button type="submit" class="btn-primary btn text-white px-4 py-2 rounded">
                                        Selanjutnya
                                    </button>
                            </form>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

@endsection
