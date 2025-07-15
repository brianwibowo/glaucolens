@extends('layouts.layout')

@section('content')
  <div class="container">
    {{-- Mengikuti pola judul seperti di view fitur lainnya --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard {{ Auth::user()->getRoleNames()->first() }}</h1>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Selamat datang di SIMILA!</h5>
            <p class="card-text">Saat ini Anda Login sebagai <strong>{{ Auth::user()->name }}</strong> dan <strong>{{ Auth::user()->getRoleNames()->first() }}</strong>.</p>
            <p class="card-text">Anda dapat mulai mengelola laman ini.</p>
        </div>
    </div>
  </div>
@endsection