@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 h4">Edit Ruang PKL</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('perusahaan-pkl-update', $pkl->id) }}" method="POST" enctype="multipart/form-data" id="pklForm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Judul</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $pkl->nama) }}" required>
        </div>        <div class="mb-3">
            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
            <input type="date"
                   name="tanggal_mulai"
                   id="tanggal_mulai"
                   class="form-control @error('tanggal_mulai') is-invalid @enderror"
                   value="{{ old('tanggal_mulai', $pkl->tanggal_mulai ? $pkl->tanggal_mulai->format('Y-m-d') : '') }}"
                   required>
            @error('tanggal_mulai')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tanggal_selesai" class="form-label">Tanggal Target Selesai</label>
            <input type="date"
                   name="tanggal_selesai"
                   id="tanggal_selesai"
                   class="form-control @error('tanggal_selesai') is-invalid @enderror"
                   value="{{ old('tanggal_selesai', $pkl->tanggal_selesai ? $pkl->tanggal_selesai->format('Y-m-d') : '') }}"
                   required>
            @error('tanggal_selesai')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-10">Ajukan</button>
    </form>
</div>

@push('scripts')
<script>
    // Ensure end date is after start date
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('tanggal_mulai');
        const endDateInput = document.getElementById('tanggal_selesai');
        
        // Set minimum end date when start date changes
        startDateInput.addEventListener('change', function() {
            endDateInput.min = startDateInput.value;
            
            // If end date is now before start date, update it
            if (endDateInput.value < startDateInput.value) {
                endDateInput.value = startDateInput.value;
            }
        });
        
        // Initial setup
        if (startDateInput.value) {
            endDateInput.min = startDateInput.value;
        }
        
        // Form validation
        document.getElementById('pklForm').addEventListener('submit', function(event) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            
            if (endDate < startDate) {
                event.preventDefault();
                alert('Tanggal selesai harus setelah atau sama dengan tanggal mulai.');
            }
        });
    });
</script>
@endpush
@endsection
