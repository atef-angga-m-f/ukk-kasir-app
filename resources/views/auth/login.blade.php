@extends('layouts.app')

@section('title', 'Login')

@section('content')

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                <img src="{{ asset('img/login-vector.jpg') }}" width="500px">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center my-5">
                                        <h1 class="h4 text-gray-900 mb-4">Selamat Datang di <span class="text-primary">Neo Kasir</span></h1>
                                        <p>Silahkan Login terlebih dahulu!</p>
                                    </div>
                                    <form method="POST" action="{{ route('login') }}" class="user">
                                        @csrf
                                    
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Masukkan Alamat Email..." required>
                                        </div>
                                    
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Masukkan Kata Sandi..." required>
                                        </div>
                                    
                                        <button type="submit" class="btn btn-primary mt-5 btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection