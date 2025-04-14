@extends('layouts.app')

@section('title', 'Dashboard')

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
                    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

                    <form id="purchase-form" action="{{ route('purchase.sale.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="products_json" id="products-json">

                        <div class="row">
                            @foreach ($products as $product)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card shadow-sm text-center p-4 rounded-lg">
                                        <img src="{{ asset('storage/' . $product->img) }}" alt="{{ $product->product_name }}"
                                            class="card-img-top mx-auto" style="width: 150px; height: auto;">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">{{ $product->product_name }}</h5>
                                            <p>Stok: {{ $product->stock }}</p>
                                            <p class="text-dark">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>

                                            <!-- Quantity Selector -->
                                            <div class="d-flex justify-content-center align-items-center mb-3">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="decreaseQuantity('{{ $product->id }}')">-</button>
                                                <input type="number" id="quantity-{{ $product->id }}" value="0" min="0" max="{{ $product->stock }}"
                                                    data-price="{{ $product->price }}" class="form-control text-center mx-2" style="width: 60px;" readonly>
                                                <button type="button" class="btn btn-success btn-sm"
                                                    onclick="increaseQuantity('{{ $product->id }}')">+</button>
                                            </div>

                                            <p class="text-danger" id="stock-kurang-{{ $product->id }}" style='display: none'>*stock kurang</p>
                                            <p class="text-danger" id="stock-habis-{{ $product->id }}" style='display: none'>*stock habis</p>
                                            <!-- Sub Total -->
                                            <p>Sub Total: <strong>Rp. <span id="total-price-{{ $product->id }}">0</span></strong></p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary mt-3">Checkout</button>
                    </form>

                    <script>
                        function decreaseQuantity(productId) {
                            const quantityInput = document.getElementById(`quantity-${productId}`);
                            if (quantityInput.value > 0) {
                                quantityInput.value = parseInt(quantityInput.value) - 1;
                                updateTotalPrice(productId);
                                document.getElementById(`stock-kurang-${productId}`).style.display = 'none';
                            } else {
                                document.getElementById(`stock-habis-${productId}`).style.display = 'block';
                            }
                        }

                        function increaseQuantity(productId) {
                            const quantityInput = document.getElementById(`quantity-${productId}`);
                            if (parseInt(quantityInput.value) < parseInt(quantityInput.max)) {
                                quantityInput.value = parseInt(quantityInput.value) + 1;
                                updateTotalPrice(productId);
                                document.getElementById(`stock-habis-${productId}`).style.display = 'none';

                            } else {
                                document.getElementById(`stock-kurang-${productId}`).style.display = 'block';
                            }
                        }

                        function updateTotalPrice(productId) {
                            const quantityInput = document.getElementById(`quantity-${productId}`);
                            const totalPriceElement = document.getElementById(`total-price-${productId}`);
                            totalPriceElement.textContent = new Intl.NumberFormat('id-ID').format(quantityInput.value * quantityInput.dataset.price);
                        }

                        document.getElementById('purchase-form').addEventListener('submit', function (event) {
                            const selectedProducts = [];

                            document.querySelectorAll('input[id^="quantity-"]').forEach(input => {
                                const productId = input.id.split('-')[1];
                                const quantity = parseInt(input.value);

                                if (quantity > 0) {
                                    selectedProducts.push({
                                        id: productId,
                                        name: document.querySelector(`#quantity-${productId}`).closest('.card-body').querySelector('.card-title').textContent,
                                        price: parseInt(input.dataset.price),
                                        quantity: quantity,
                                        total: parseInt(input.dataset.price) * quantity
                                    });
                                }
                            });

                            if (selectedProducts.length === 0) {
                                alert("Pilih setidaknya satu produk.");
                                event.preventDefault();
                                return;
                            }

                            document.getElementById('products-json').value = JSON.stringify(selectedProducts);
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
