@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Daftar Beasiswa Aktif dari Perusahaan</h3>

    @if ($batches->count() > 0)
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Batch</th>
                                <th>Perusahaan</th>
                                <th>Periode Pendaftaran</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($batches as $index => $batch)
                                <tr>
                                    <td>{{ $batches->firstItem() + $index }}</td>
                                    <td>{{ $batch->batch }}</td>
                                    <td>{{ $batch->perusahaan->nama_perusahaan ?? '-' }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($batch->tanggal_mulai)->format('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($batch->tanggal_selesai)->format('d M Y') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-end mt-3">
                    {{ $batches->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning text-center">
            Tidak ada batch beasiswa aktif saat ini.
        </div>
    @endif
</div>
@endsection
