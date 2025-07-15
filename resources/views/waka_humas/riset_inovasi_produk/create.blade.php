@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 h4">Ajukan Riset/Inovasi Produk</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('waka-humas-riset-store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="topik" class="form-label">Topik Riset</label>
            <input type="text" name="topik" id="topik" class="form-control" value="{{ old('topik') }}" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" required>{{ old('deskripsi') }}</textarea>
        </div>
        
        {{-- ================= KODE BARU UNTUK ANGGOTA TIM ================= --}}
        <div class="mb-3">
            <label for="anggota_select" class="form-label">Anggota Tim</label>
            
            {{-- Dropdown untuk memilih anggota --}}
            <select id="anggota_select" class="form-select">
                <option value="" selected disabled>-- Pilih anggota untuk ditambahkan --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" data-name="{{ $user->name }} ({{ $user->role }})">{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
            
            {{-- Container untuk menampilkan badge anggota yang dipilih --}}
            <div id="anggota_badges_container" class="mt-2 d-flex flex-wrap gap-2">
                {{-- Badge akan muncul di sini --}}
            </div>
        
            {{-- Container tersembunyi untuk menyimpan input yang akan dikirim ke server --}}
            <div id="hidden_inputs_container">
                {{-- Input tersembunyi akan ditambahkan di sini oleh JavaScript --}}
            </div>
        </div>
        {{-- ================= AKHIR KODE BARU ================= --}}

        <div class="mb-3">
            <label for="file_proposal" class="form-label">File Proposal (PDF, maks. 10MB)</label>
            <input type="file" name="file_proposal" id="file_proposal" class="form-control" accept=".pdf" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajukan</button>
        <a href="{{ route('waka-humas-riset-index') }}" class="btn btn-secondary ms-2">Kembali</a>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAnggota = document.getElementById('anggota_select');
    const badgesContainer = document.getElementById('anggota_badges_container');
    const hiddenInputsContainer = document.getElementById('hidden_inputs_container');
    const form = selectAnggota.closest('form');

    function addAnggota(id, name) {
        if (document.querySelector(`.badge-anggota[data-id="${id}"]`)) {
            return;
        }

        const badge = document.createElement('span');
        badge.className = 'badge bg-primary d-flex align-items-center gap-2 badge-anggota';
        badge.dataset.id = id;
        badge.innerHTML = `
            ${name}
            <button type="button" class="btn-close btn-close-white" aria-label="Close" style="font-size: 0.65em;"></button>
        `;

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'tim_riset[]';
        hiddenInput.value = id;
        hiddenInput.dataset.id = id;

        badgesContainer.appendChild(badge);
        hiddenInputsContainer.appendChild(hiddenInput);

        const option = selectAnggota.querySelector(`option[value="${id}"]`);
        if(option) {
            option.style.display = 'none';
        }
        
        selectAnggota.value = '';
    }

    selectAnggota.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (!selectedOption || !selectedOption.value) return;

        const id = selectedOption.value;
        const name = selectedOption.dataset.name;
        
        addAnggota(id, name);
    });

    badgesContainer.addEventListener('click', function(e) {
        if (e.target.matches('.btn-close')) {
            const badge = e.target.closest('.badge-anggota');
            const id = badge.dataset.id;

            badge.remove();

            const hiddenInput = hiddenInputsContainer.querySelector(`input[data-id="${id}"]`);
            if (hiddenInput) {
                hiddenInput.remove();
            }

            const option = selectAnggota.querySelector(`option[value="${id}"]`);
            if (option) {
                option.style.display = 'block';
            }
        }
    });

    const oldTimRiset = {!! json_encode(old('tim_riset', [])) !!};
    if (oldTimRiset.length > 0) {
        oldTimRiset.forEach(id => {
            const option = selectAnggota.querySelector(`option[value="${id}"]`);
            if(option) {
                const name = option.dataset.name;
                addAnggota(id, name);
            }
        });
    }
});
</script>
@endsection