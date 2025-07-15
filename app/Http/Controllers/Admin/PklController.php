<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PKL;
use App\Models\User;
use App\Models\Logbook;
use App\Models\LogbookContent;
use Illuminate\Support\Facades\Storage;

class PklController extends Controller
{
    /**
     * Display a listing of all PKL programs.
     */
    public function index()
    {
        $pkls = PKL::with(['perusahaan', 'pembimbing', 'siswas'])->latest()->paginate(10);
        
        return view('admin.pkl.index', [
            'pkls' => $pkls
        ]);
    }

    /**
     * Show form to select company to represent
     */
    public function selectCompany()
    {
        $companies = User::role('perusahaan')->get();
        return view('admin.pkl.select_company', [
            'companies' => $companies
        ]);
    }
    
    /**
     * Display PKLs for the selected company
     */
    public function representCompany(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:users,id'
        ]);
        
        // Store in session which company admin is representing
        session(['admin_representing_company' => $request->company_id]);
        
        $company = User::find($request->company_id);
        $pkls = PKL::where('perusahaan_id', $request->company_id)
                   ->orWhere('admin_representing', $request->company_id)
                   ->with(['pembimbing', 'siswas'])
                   ->get();
        
        return view('admin.pkl.represent_company', [
            'pkls' => $pkls,
            'company' => $company
        ]);
    }

    /**
     * Show the form for creating a new PKL for a company.
     */
    public function createForCompany(User $company)
    {
        return view('admin.pkl.create_for_company', [
            'company' => $company
        ]);
    }

    /**
     * Store a newly created PKL for a company.
     */
    public function storeForCompany(Request $request, User $company)
    {
        $request->validate([
            'nama' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
        ]);

        PKL::create([
            'nama' => $request->nama,
            'perusahaan_id' => $company->id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => 'proses',
            'status_pembimbing' => 'proses',
            'status_waka_humas' => 'proses',
            'created_by' => auth()->id(),
            'admin_representing' => $company->id,
            'admin_representing_role' => 'perusahaan'
        ]);

        return redirect()->route('admin-pkl-represent-company', ['company_id' => $company->id])
                        ->with('success', 'Program PKL berhasil dibuat untuk ' . $company->name);
    }

    /**
     * Show the form for editing a PKL for a company.
     */
    public function editForCompany(User $company, PKL $pkl)
    {
        return view('admin.pkl.edit_for_company', [
            'company' => $company,
            'pkl' => $pkl
        ]);
    }

    /**
     * Update the specified PKL for a company.
     */
    public function updateForCompany(Request $request, User $company, PKL $pkl)
    {
        $request->validate([
            'nama' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
        ]);

        $pkl->update([
            'nama' => $request->nama,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai
        ]);

        return redirect()->route('admin-pkl-represent-company', ['company_id' => $company->id])
                        ->with('success', 'Program PKL berhasil diupdate');
    }

    /**
     * Remove the specified PKL.
     */
    public function destroyForCompany(User $company, PKL $pkl)
    {
        // Check if there are students enrolled in this PKL
        $hasEnrolledStudents = $pkl->siswas()->count() > 0;
        
        if ($hasEnrolledStudents) {
            return redirect()->route('admin-pkl-represent-company', ['company_id' => $company->id])
                    ->with('error', 'PKL tidak dapat dihapus karena sudah ada siswa yang terdaftar.');
        }
        
        $pkl->delete();
        
        return redirect()->route('admin-pkl-represent-company', ['company_id' => $company->id])
                ->with('success', 'PKL berhasil dihapus.');
    }

    /**
     * Display students for a PKL program.
     */
    public function studentsForCompany(User $company, PKL $pkl)
    {
        $students = User::where('pkl_id', $pkl->id)->get();
        
        return view('admin.pkl.students_for_company', [
            'company' => $company,
            'pkl' => $pkl,
            'students' => $students
        ]);
    }

    /**
     * Approve a student for PKL on behalf of company.
     */
    public function approveStudentForCompany(User $company, User $user)
    {
        $user->pkl_status = 'disetujui';
        $user->save();

        // Create logbook for the student
        Logbook::create([
            'siswa_id' => $user->id,
            'pkl_id' => $user->pkl_id,
            'status' => 'proses',
        ]);
        
        // Update PKL status to 'berjalan' when student is approved
        $pkl = PKL::find($user->pkl_id);
        if ($pkl) {
            $pkl->status = 'berjalan';
            $pkl->save();
        }

        return redirect()->route('admin-pkl-students-for-company', ['company' => $company->id, 'pkl' => $user->pkl_id])
                ->with('success', 'Siswa berhasil disetujui untuk PKL');
    }

    /**
     * Reject a student from PKL on behalf of company.
     */
    public function rejectStudentForCompany(User $company, User $user)
    {
        $user->pkl_status = 'tidak_disetujui';
        $user->pkl_id = null;
        $user->save();
        
        return redirect()->route('admin-pkl-students-for-company', ['company' => $company->id, 'pkl' => $user->pkl_id])
                ->with('success', 'Siswa berhasil ditolak dari PKL');
    }

    /**
     * Grade a student's PKL performance on behalf of company.
     */
    public function gradeStudentForCompany(Request $request, User $company, User $user)
    {
        $request->validate([
            'nilai' => 'required|integer|min:0|max:100'
        ]);

        $user->update([
            'nilai_pkl' => $request->nilai
        ]);

        return redirect()->route('admin-pkl-students-for-company', ['company' => $company->id, 'pkl' => $user->pkl_id])
                ->with('success', 'Nilai PKL siswa berhasil diperbarui');
    }

    /**
     * Display form to select guru to represent.
     */
    public function selectGuru()
    {
        $gurus = User::role('guru')->get();
        return view('admin.pkl.select_guru', [
            'gurus' => $gurus
        ]);
    }

    /**
     * Display PKLs for the selected guru.
     */
    public function representGuru(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:users,id'
        ]);
        
        // Store in session which guru admin is representing
        session(['admin_representing_guru' => $request->guru_id]);
        
        $guru = User::find($request->guru_id);
        $pkls = PKL::where('pembimbing_id', $request->guru_id)
                    ->with(['perusahaan', 'siswas'])
                    ->get();
        
        return view('admin.pkl.represent_guru', [
            'pkls' => $pkls,
            'guru' => $guru
        ]);
    }

    /**
     * Display logbook for a student on behalf of guru.
     */
    public function siswaLogbook(User $guru, User $siswa)
    {
        $logbook = Logbook::where('siswa_id', $siswa->id)->first();
        
        if (!$logbook) {
            return redirect()->route('admin-pkl-represent-guru', ['guru_id' => $guru->id])
                ->with('error', 'Logbook siswa tidak ditemukan');
        }
        
        // Get logbook contents ordered by date (newest first)
        $logbookContents = $logbook->logbookContents()
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
        
        $pkl = PKL::find($logbook->pkl_id);
        
        return view('admin.pkl.siswa_logbook', [
            'guru' => $guru,
            'siswa' => $siswa,
            'logbook' => $logbook,
            'logbookContents' => $logbookContents,
            'pkl' => $pkl
        ]);
    }

    /**
     * Validate a student's final report on behalf of guru.
     */
    public function validateReportForGuru(Request $request, User $guru, User $siswa)
    {
        $request->validate([
            'status' => 'required|in:disetujui,revisi',
            'catatan' => 'nullable|string|max:1000'
        ]);
        
        if (!$siswa->pkl_id) {
            return redirect()->route('admin-pkl-represent-guru', ['guru_id' => $guru->id])
                ->with('error', 'Siswa tidak terdaftar dalam program PKL');
        }
        
        $pkl = PKL::find($siswa->pkl_id);
        
        // Update the validation status in PKL record
        $pkl->update([
            'status_pembimbing' => $request->status,
            'catatan_pembimbing' => $request->catatan ?? null,
            'tanggal_validasi_pembimbing' => now()
        ]);
        
        return redirect()->route('admin-pkl-represent-guru', ['guru_id' => $guru->id])
            ->with('success', 'Laporan akhir PKL berhasil divalidasi atas nama ' . $guru->name);
    }

    /**
     * Display list of PKL programs for pembimbing assignment.
     */
    public function assignPembimbingList()
    {
        $pkls = PKL::latest()->paginate(10);
        return view('admin.pkl.assign_pembimbing_list', [
            'pkls' => $pkls
        ]);
    }

    /**
     * Show form to assign pembimbing to PKL.
     */
    public function assignPembimbingForm(PKL $pkl)
    {
        $pembimbings = User::role('guru')->get();
        
        return view('admin.pkl.assign_pembimbing_form', [
            'pkl' => $pkl,
            'pembimbings' => $pembimbings
        ]);
    }

    /**
     * Store pembimbing assignment for PKL.
     */
    public function assignPembimbingStore(Request $request, PKL $pkl)
    {
        $request->validate([
            'pembimbing_id' => 'required|exists:users,id'
        ]);

        $pkl->update([
            'pembimbing_id' => $request->pembimbing_id,
            'admin_representing_role' => 'waka_humas'
        ]);

        return redirect()->route('admin-pkl-assign-pembimbing-list')
                ->with('success', 'Pembimbing berhasil ditugaskan untuk program PKL');
    }

    /**
     * Validate PKL report on behalf of waka humas.
     */
    public function validateReport(Request $request, PKL $pkl)
    {
        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string|max:1000'
        ]);

        $pkl->update([
            'status_waka_humas' => $validated['status'],
            'catatan_waka_humas' => $validated['catatan'] ?? null,
            'tanggal_validasi_waka_humas' => now(),
            'admin_representing_role' => 'waka_humas'
        ]);

        return redirect()->route('admin-pkl-index')
            ->with('success', 'Laporan PKL berhasil divalidasi sebagai Waka Humas');
    }
}
