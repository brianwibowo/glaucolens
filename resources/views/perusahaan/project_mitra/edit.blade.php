@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    {{-- Judul diubah untuk menandakan halaman edit --}}
    <h1 class="mb-4 h4">Edit Project</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Form action dan method disesuaikan untuk update --}}
    <form action="{{ route('perusahaan-project-update', $project->id) }}" method="POST" enctype="multipart/form-data" id="projectForm">
        @csrf
        @method('PUT') {{-- Metode HTTP untuk update --}}

        <div class="mb-3">
            <label for="judul" class="form-label">Judul Project</label>
            <input type="text"
                   name="judul"
                   id="judul"
                   class="form-control @error('judul') is-invalid @enderror"
                   {{-- Mengisi value dengan data lama atau data dari database --}}
                   value="{{ old('judul', $project->judul) }}"
                   required>
            @error('judul')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi Project</label>
            <textarea name="deskripsi"
                      id="deskripsi"
                      rows="4"
                      class="form-control @error('deskripsi') is-invalid @enderror"
                      required>{{ old('deskripsi', $project->deskripsi) }}</textarea>
            @error('deskripsi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>        <div class="mb-3">
            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
            <input type="date"
                   name="tanggal_mulai"
                   id="tanggal_mulai"
                   class="form-control @error('tanggal_mulai') is-invalid @enderror"
                   value="{{ old('tanggal_mulai', $project->tanggal_mulai) }}"
                   required>
            @error('tanggal_mulai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">
                <small class="text-muted"><i class="bi bi-info-circle"></i> Anda dapat memilih tanggal project dari tahun kapanpun, termasuk project lama atau terdahulu.</small>
            </div>
        </div>

        <div class="mb-3">
            <label for="tanggal_selesai" class="form-label">Tanggal Target Selesai</label>
            <input type="date"
                   name="tanggal_selesai"
                   id="tanggal_selesai"
                   class="form-control @error('tanggal_selesai') is-invalid @enderror"
                   value="{{ old('tanggal_selesai', $project->tanggal_selesai) }}"
                   required>
            @error('tanggal_selesai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">
                <small class="text-muted"><i class="bi bi-info-circle"></i> Anda dapat memilih tanggal project dari tahun kapanpun, pastikan tanggal selesai setelah tanggal mulai.</small>
            </div>
        </div>

        <div class="alert alert-info d-none mb-3" id="duration-info">
             <i class="bi bi-clock"></i> <strong>Durasi Project:</strong> <span id="duration-text"></span>
        </div>

        <div class="mb-4">
            <label for="file_brief" class="form-label">File Brief Project</label>
            {{-- Menampilkan file yang sudah ada --}}
            @if($project->file_brief)
                <div class="mb-2">
                    <small class="text-muted">File saat ini: 
                        <a href="{{ Storage::url($project->file_brief) }}" target="_blank">Lihat File</a>
                    </small>
                </div>
            @endif
            
            <input type="file"
                   name="file_brief"
                   id="file_brief"
                   class="form-control @error('file_brief') is-invalid @enderror"
                   accept=".pdf"> {{-- Atribut 'required' dihapus --}}
            <div class="form-text">
                <small class="text-muted">Format: PDF (Max: 10MB). Kosongkan jika tidak ingin mengubah file.</small>
            </div>
             @error('file_brief')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Teks tombol diubah --}}
        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan Perubahan</button>
        <a href="{{ route('perusahaan-project-index') }}" class="btn btn-outline-secondary">
             Batal
        </a>
    </form>
</div>

{{-- SCRIPT DIBAWAH INI SUDAH DIHAPUS BAGIAN LOCALSTORAGE-NYA --}}
<script>
 document.addEventListener('DOMContentLoaded', function() {
     const startDateInput = document.getElementById('tanggal_mulai');
     const endDateInput = document.getElementById('tanggal_selesai');
     const durationInfo = document.getElementById('duration-info');
     const durationText = document.getElementById('duration-text');
     const form = document.getElementById('projectForm');
     const submitBtn = document.getElementById('submitBtn');     function calculateDuration() {
         const startDate = new Date(startDateInput.value);
         const endDate = new Date(endDateInput.value);
         
         if (startDate && endDate && startDate < endDate) {
             const diffTime = Math.abs(endDate - startDate);
             const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
             
             durationText.textContent = `${diffDays} hari`;
             durationInfo.classList.remove('d-none');
             
             // We won't update end date minimum anymore to allow any date in current year
             // endDateInput.min = startDateInput.value;
         } else {
             durationInfo.classList.add('d-none');
         }
     }

     function validateDates() {
         const startDate = new Date(startDateInput.value);
         const endDate = new Date(endDateInput.value);
         
         if (startDate && endDate && startDate >= endDate) {
             endDateInput.setCustomValidity('Tanggal selesai harus setelah tanggal mulai');
         } else {
             endDateInput.setCustomValidity('');
         }
     }

     startDateInput.addEventListener('change', function() {
         calculateDuration();
         validateDates();
     });

     endDateInput.addEventListener('change', function() {
         calculateDuration();
         validateDates();
     });

     form.addEventListener('submit', function(e) {
         submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';
         submitBtn.disabled = true;
     });

     document.getElementById('file_brief').addEventListener('change', function() {
         const file = this.files[0];
         if (file && file.size > 10 * 1024 * 1024) { // 10MB
             alert('Ukuran file terlalu besar. Maksimal 10MB.');
             this.value = '';
         }
     });

     const deskripsiTextarea = document.getElementById('deskripsi');
     const charCounter = document.createElement('div');
     charCounter.className = 'form-text text-end';
     charCounter.style.marginTop = '5px';
     deskripsiTextarea.parentNode.appendChild(charCounter);

     function updateCharCounter() {
         const currentLength = deskripsiTextarea.value.length;
         charCounter.innerHTML = `<small class="text-muted">${currentLength} karakter</small>`;
     }

     deskripsiTextarea.addEventListener('input', updateCharCounter);
     updateCharCounter();
     
     // Memanggil fungsi kalkulasi durasi saat halaman dimuat untuk menampilkan durasi awal
     calculateDuration();
 });
</script>

{{-- STYLE TIDAK PERLU DIUBAH --}}
<style>
    .card {
        border: none;
        border-radius: 15px;
    }
    
    .card-header {
        border-radius: 15px 15px 0 0 !important;
    }
    
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .btn {
        border-radius: 25px;
    }
    
    .alert {
        border-radius: 10px;
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
</style>
@endsection