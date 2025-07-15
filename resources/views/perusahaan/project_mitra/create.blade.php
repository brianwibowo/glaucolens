@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 h4">Ajukan Project</h1>

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

    <form action="{{ route('perusahaan-project-store') }}" method="POST" enctype="multipart/form-data" id="projectForm">
        @csrf

        <div class="mb-3">
            <label for="judul" class="form-label">Judul Project</label>
            <input type="text"
                   name="judul"
                   id="judul"
                   class="form-control @error('judul') is-invalid @enderror"
                   placeholder="Masukkan judul project"
                   required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi Project</label>
            <textarea name="deskripsi"
                      id="deskripsi"
                      rows="4"
                      class="form-control @error('deskripsi') is-invalid @enderror"
                      placeholder="Masukkan detail project"
                      required></textarea>
        </div>        <div class="mb-3">
            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
            <input type="date"
                   name="tanggal_mulai"
                   id="tanggal_mulai"
                   class="form-control @error('tanggal_mulai') is-invalid @enderror"
                   required>
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
                   required>
            <div class="form-text">
                <small class="text-muted"><i class="bi bi-info-circle"></i> Anda dapat memilih tanggal project dari tahun kapanpun, pastikan tanggal selesai setelah tanggal mulai.</small>
            </div>
        </div>

        {{-- Elemen ini dikontrol oleh JavaScript dan akan muncul jika tanggal valid --}}
        <div class="alert alert-info d-none mb-3" id="duration-info">
             <i class="bi bi-clock"></i> <strong>Durasi Project:</strong> <span id="duration-text"></span>
        </div>

        <div class="mb-4">
            <label for="file_brief" class="form-label">File Brief Project</label>
            <input type="file"
                   name="file_brief"
                   id="file_brief"
                   class="form-control @error('file_brief') is-invalid @enderror"
                   accept=".pdf"
                   required>
             <div class="form-text">
                <small class="text-muted">Format: PDF (Max: 10MB)</small>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" id="submitBtn">Ajukan</button>
        <a href="{{ route('perusahaan-project-index') }}" class="btn btn-outline-secondary">
             Kembali
        </a>
    </form>
</div>

{{-- SCRIPT DAN STYLE TETAP SAMA, TIDAK PERLU DIUBAH --}}
<script>
 // Date validation and duration calculation
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

     // Event listeners
     startDateInput.addEventListener('change', function() {
         calculateDuration();
         validateDates();
     });

     endDateInput.addEventListener('change', function() {
         calculateDuration();
         validateDates();
     });

     // Form submission
     form.addEventListener('submit', function(e) {
         submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengajukan...';
         submitBtn.disabled = true;
     });

     // File size validation
     document.getElementById('file_brief').addEventListener('change', function() {
         const file = this.files[0];
         if (file && file.size > 10 * 1024 * 1024) { // 10MB
             alert('Ukuran file terlalu besar. Maksimal 10MB.');
             this.value = '';
         }
     });

     // Character counter for description
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
     updateCharCounter(); // Initial count
 });

 // Auto-save draft (optional feature)
 function saveDraft() {
     const formData = {
         judul: document.getElementById('judul').value,
         deskripsi: document.getElementById('deskripsi').value,
         tanggal_mulai: document.getElementById('tanggal_mulai').value,
         tanggal_selesai: document.getElementById('tanggal_selesai').value
     };
     
     localStorage.setItem('project_draft', JSON.stringify(formData));
 }

 function loadDraft() {
     const draft = localStorage.getItem('project_draft');
     if (draft) {
         const data = JSON.parse(draft);
         if (confirm('Ditemukan draft yang belum disimpan. Muat draft?')) {
             document.getElementById('judul').value = data.judul || '';
             document.getElementById('deskripsi').value = data.deskripsi || '';
             document.getElementById('tanggal_mulai').value = data.tanggal_mulai || '';
             document.getElementById('tanggal_selesai').value = data.tanggal_selesai || '';
         }
     }
 }

 // Load draft on page load
 window.addEventListener('load', loadDraft);

 // Save draft periodically
 setInterval(saveDraft, 30000); // Every 30 seconds

 // Clear draft on successful submission
 document.getElementById('projectForm').addEventListener('submit', function() {
     localStorage.removeItem('project_draft');
 });
</script>

<style>
    /* STYLE INI TIDAK PERLU DIUBAH KARENA SUDAH UMUM */
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