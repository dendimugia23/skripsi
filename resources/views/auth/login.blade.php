@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100" style="background: #58a6d5; margin: 0; padding: 0;">
    <div class="card shadow-lg">
        <div class="card-body text-center p-4 p-md-5">
            <h4 class="mb-4 font-weight-bold">
                <a href="{{ route('index') }}" style="color: inherit; text-decoration: none;">
                    {{ __('SIG WiFi Publik Kab. Garut') }}
                </a>
            </h4>
            <p class="mb-4">{{ __('Masukan Email & Password Untuk Login') }}</p>

            <!-- Tampilkan pesan error -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tampilkan pesan sukses -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Input -->
                <div class="mb-4">
                    <label for="email" class="form-label visually-hidden">{{ __('Email Address') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input id="email" type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" 
                               required autocomplete="email" placeholder="Email">
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="mb-4">
                    <label for="password" class="form-label visually-hidden">{{ __('Password') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input id="password" type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="current-password" 
                               placeholder="Password">
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Login Button -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Login') }}
                    </button>
                </div>
            </form>

            <!-- Teks Kembali -->
            <div class="mt-3">
                <a href="{{ route('index') }}" class="text-muted small">
                    {{ __('Kembali ke Halaman Utama') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection