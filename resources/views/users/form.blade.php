@extends('layouts.app')

@section('title', 'Tambah User')

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
                        <h1 class="h3 text-gray-800">{{ isset($user) ? 'Edit User' : 'Tambah User' }}</h1>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-5">
                                        <form
                                            action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}"
                                            method="POST" class="user">
                                            @csrf
                                            @if (isset($user))
                                                @method('PUT')
                                            @endif
                                            <div class="form-group row mb-4">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <label for="email">Email : </label>
                                                    <input type="text" class="form-control" name="email" id="email"
                                                        placeholder="Masukkan Email..." value="{{ isset($user) ? $user->email : '' }}" required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="name">Nama : </label>
                                                    <input type="text" class="form-control" name="name" id="nama"
                                                        placeholder="Masukkan Nama..."  value="{{ isset($user) ? $user->name : '' }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <label for="role">Role : </label>
                                                    <select class="form-control" name="role" id="role" required>
                                                        <option value="">Pilih Role</option>
                                                        <option value="admin" {{  isset($user) && $user->role == 'admin' ? 'selected' : ''}} >Admin</option>
                                                        <option value="cashier" {{  isset($user) && $user->role == 'cashier' ? 'selected' : ''}} >Cashier</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="password">Password : </label>
                                                    <input type="password" class="form-control" name="password" id="password"
                                                        placeholder="Masukkan Password..." value="{{ isset($user) ? $user->password : '' }}" required>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn mt-5 btn-primary btn-user btn-block">
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
