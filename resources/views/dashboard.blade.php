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

                    <!-- Content Row -->




                    @if (auth()->user()->role == 'admin')
                        <form method="GET" class="mb-3">
                            <label for="range">Pilih Rentang Waktu:</label>
                            <select name="range" onchange="this.form.submit()" class="form-control w-auto d-inline-block ml-2">
                                <option value="day" {{ $range == 'day' ? 'selected' : '' }}>Harian</option>
                                <option value="week" {{ $range == 'week' ? 'selected' : '' }}>Mingguan</option>
                                <option value="month" {{ $range == 'month' ? 'selected' : '' }}>Bulanan</option>
                            </select>
                        </form>
                        <div class="row">

                            <!-- Area Chart -->
                            <div class="col-xl-8 col-lg-7">
                                <div class="card shadow mb-4">
                                    <!-- Card Header - Dropdown -->
                                    <div
                                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            Jumlah Penjualan</h6>

                                        </div>
                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <div class="chart-area">
                                            <canvas id="myAreaChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <!-- Pie Chart -->
                            <div class="col-xl-4 col-lg-5">
                                <div class="card shadow mb-4">
                                    <!-- Card Header - Dropdown -->
                                    <div
                                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            Presentase Penjualan Produk</h6>

                                        </div>
                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <div class="chart-pie pt-4 pb-2">
                                            <canvas id="myPieChart"></canvas>
                                            </div>
                                        <div class="m-5">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <h5>Periode: {{ $startDate }} - {{ $endDate }}</h5>
                                <h5>Total Keuntungan: Rp {{ number_format($totalProfit, 0, ',', '.') }}</h5>
                            </div>

                    @endif

                    @if (auth()->user()->role == 'cashier')
                        <!-- Basic Card Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h5 class="m-0 font-weight-bold text-primary">Selamat Datang,
                                    Kasir!</h5>
                                </div>
                            <div class="bg-gray-100 rounded-xl p-5 text-center">
                                <h3 class="text-lg font-medium mb-3">Total Penjualan Hari Ini
                                </h3>
                                <h1 class="text-4xl font-bold text-gray-800 mt-3 mb-3">
                                    {{ $todaySalesCount }}</h1>
                                <h3 class="text-lg mt-2">Jumlah total penjualan yang terjadi
                                    hari ini.</h3>
                                <p class="text-sm mt-4">
                                    Terakhir diperbarui:
                                    {{ \Carbon\Carbon::parse($lastUpdate)->format('d M Y H:i') }}
                                    </p>

                                </div>
                            </div>
                    @endif

                    </div>
                <!-- /.container-fluid -->

                </div>
            <!-- End of Main Content -->


            <script>
                const salesLabels = {!! json_encode($labels) !!};
                const salesTotals = {!! json_encode($totals) !!};
                const productLabels = {!! $productLabels !!};
                const productTotals = {!! $productTotals !!};
              </script>


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
