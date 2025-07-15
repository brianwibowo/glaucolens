@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="bi bi-info-circle-fill me-2"></i>
        <div>
            <span class="fw-bold">Mode Admin sebagai Perusahaan:</span> 
            Anda saat ini bertindak atas nama perusahaan <strong>{{ $company->name }}</strong>
        </div>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Kelola Siswa PKL - {{ $pkl->nama }}</h1>
        <a href="{{ route('admin-pkl-represent-company', ['company_id' => $company->id]) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <ul class="nav nav-tabs card-header-tabs" id="studentsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">
                        Pendaftar Baru
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="false">
                        Siswa Disetujui
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false">
                        Siswa Ditolak
                    </button>
                </li>
            </ul>
        </div>
        
        <div class="card-body">
            <div class="tab-content" id="studentsTabContent">
                <!-- Tab Pendaftar Baru -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Email</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $pendingCount = 0; @endphp
                                @foreach($students as $index => $student)
                                    @if($student->pkl_status == 'proses')
                                        @php $pendingCount++; @endphp
                                        <tr>
                                            <td>{{ $pendingCount }}</td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->email }}</td>
                                            <td>{{ $student->kelas ?? 'N/A' }}</td>
                                            <td><span class="badge bg-warning text-dark">Menunggu</span></td>
                                            <td>
                                                <div class="btn-group">
                                                    <form action="{{ route('admin-pkl-approve-student-for-company', ['company' => $company->id, 'user' => $student->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success me-1" onclick="return confirm('Apakah Anda yakin ingin menyetujui siswa ini?')">
                                                            <i class="bi bi-check-lg"></i> Terima
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('admin-pkl-reject-student-for-company', ['company' => $company->id, 'user' => $student->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menolak siswa ini?')">
                                                            <i class="bi bi-x-lg"></i> Tolak
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                
                                @if($pendingCount == 0)
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada pendaftar baru</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Tab Siswa Disetujui -->
                <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Email</th>
                                    <th>Kelas</th>
                                    <th>Nilai PKL</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $approvedCount = 0; @endphp
                                @foreach($students as $index => $student)
                                    @if($student->pkl_status == 'disetujui')
                                        @php $approvedCount++; @endphp
                                        <tr>
                                            <td>{{ $approvedCount }}</td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->email }}</td>
                                            <td>{{ $student->kelas ?? 'N/A' }}</td>
                                            <td>
                                                @if($student->nilai_pkl)
                                                    <span class="badge bg-success">{{ $student->nilai_pkl }}</span>
                                                @else
                                                    <span class="badge bg-secondary">Belum Dinilai</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#gradeModal{{ $student->id }}">
                                                    <i class="bi bi-award"></i> Beri Nilai
                                                </button>
                                                
                                                <!-- Modal Pemberian Nilai -->
                                                <div class="modal fade" id="gradeModal{{ $student->id }}" tabindex="-1" aria-labelledby="gradeModalLabel{{ $student->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="gradeModalLabel{{ $student->id }}">Beri Nilai PKL - {{ $student->name }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('admin-pkl-grade-student-for-company', ['company' => $company->id, 'user' => $student->id]) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="nilai" class="form-label">Nilai PKL (0-100)</label>
                                                                        <input type="number" class="form-control" id="nilai" name="nilai" min="0" max="100" value="{{ $student->nilai_pkl ?? '' }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                
                                @if($approvedCount == 0)
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada siswa yang disetujui</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Tab Siswa Ditolak -->
                <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Email</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $rejectedCount = 0; @endphp
                                @foreach($students as $index => $student)
                                    @if($student->pkl_status == 'tidak_disetujui')
                                        @php $rejectedCount++; @endphp
                                        <tr>
                                            <td>{{ $rejectedCount }}</td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->email }}</td>
                                            <td>{{ $student->kelas ?? 'N/A' }}</td>
                                            <td><span class="badge bg-danger">Ditolak</span></td>
                                        </tr>
                                    @endif
                                @endforeach
                                
                                @if($rejectedCount == 0)
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada siswa yang ditolak</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
