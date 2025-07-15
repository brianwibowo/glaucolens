<?php

namespace App\Http\Controllers\Perusahaan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;

use App\Models\PKL;
use App\Models\User;
use App\Models\Logbook;
use Illuminate\Support\Facades\Auth;

class PklController extends Controller
{
    public function index()
    {
        return view('perusahaan.pkl.index', [
            'pkls' => Auth::user()->pklPerusahaan()->with('pembimbing')->get()
        ]);
    }
    public function show(PKL $pkl)
    {
        $pkl->load(['pembimbing', 'siswas', 'perusahaan']);
        return view('perusahaan.pkl.show', [
            'pkl' => $pkl
        ]);
    }

    public function create(){
        return view('perusahaan.pkl.create');
    }

    public function store(Request $request){
        $request->validate([
            'nama' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required'
        ]);

        PKL::create([
            'nama' => $request->nama,
            'perusahaan_id' => Auth::user()->id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => 'proses',
            'status_pembimbing' => 'proses',
            'status_waka_humas' => 'proses'
        ]);

        return redirect()->route('perusahaan-pkl-index')->with('success', 'Kelompok PKL berhasil diajukan.');
    }

    public function edit(PKL $pkl){
        return view('perusahaan.pkl.edit', [
            'pkl' => $pkl
        ]);
    }
    public function update(Request $request, PKL $pkl){
        $request->validate([
            'nama' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.'
        ]);

        $hasEnrolledStudents = $pkl->siswas()->count() > 0;
        $isChangingDatesSignificantly = false;

        if ($hasEnrolledStudents) {
            $newStartDate = \Carbon\Carbon::parse($request->tanggal_mulai);
            $newEndDate = \Carbon\Carbon::parse($request->tanggal_selesai);
            $oldStartDate = $pkl->tanggal_mulai;
            $oldEndDate = $pkl->tanggal_selesai;

            if ($newStartDate->diffInDays($oldStartDate) > 7 ||
                ($oldEndDate->diffInDays($oldStartDate) - $newEndDate->diffInDays($newStartDate) > 7)) {
                $isChangingDatesSignificantly = true;
            }
        }

        $pkl->update([
            'nama' => $request->nama,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai
        ]);

        if ($hasEnrolledStudents && $isChangingDatesSignificantly) {
            return redirect()->route('perusahaan-pkl-index')
                ->with('warning', 'PKL berhasil diupdate, tetapi perubahan tanggal signifikan telah dilakukan. Mohon informasikan perubahan ini kepada siswa dan pembimbing.');
        }

        return redirect()->route('perusahaan-pkl-index')
            ->with('success', 'PKL berhasil diupdate.');
    }
    public function destroy(PKL $pkl){
        $hasEnrolledStudents = $pkl->siswas()->count() > 0;

        if ($hasEnrolledStudents) {
            return redirect()->route('perusahaan-pkl-index')
                    ->with('error', 'PKL tidak dapat dihapus karena sudah ada siswa yang terdaftar.');
        }

        $pkl->delete();
        return redirect()->route('perusahaan-pkl-index')
                ->with('success', 'PKL berhasil dihapus.');
    }
    public function list(Request $request){
        $pkl_ids = Auth::user()->pklPerusahaan()->pluck('id')->toArray();

        $query = User::whereIn('pkl_id', $pkl_ids);

        // Filter berdasarkan status arsip (tetap sama)
        if ($request->has('archived') && $request->get('archived') == 'true') {
            $query->where('is_archived', true);
        } else {
            $query->where('is_archived', false);
            // Tambahkan filter untuk hanya menampilkan status yang relevan di daftar aktif
            $query->whereIn('pkl_status', ['proses', 'disetujui', 'tidak_disetujui']);
        }

        $users = $query->with('pklSiswa.pembimbing')->get();

        return view('perusahaan.pkl.list', [
            'siswas' => $users
        ]);
    }
    public function terima(User $user){
        $user->pkl_status = 'disetujui';
        $user->save();

        Logbook::create([
            'siswa_id' => $user->id,
            'pkl_id' => $user->pkl_id,
            'status' => 'proses',
        ]);

        $pkl = PKL::find($user->pkl_id);
        if ($pkl) {
            $pkl->status = 'berjalan';
            $pkl->save();
        }

        return redirect()->route('perusahaan-pkl-list')->with('success', 'Siswa berhasil disetujui untuk mengikuti PKL');
    }

    public function tolak(User $user){
        $user->pkl_status = 'tidak_disetujui';
        $user->pkl_id = null; // Menolak berarti memutuskan hubungan PKL
        $user->save();

        // Hapus logbook terkait jika ada, karena tidak lagi PKL
        Logbook::where('siswa_id', $user->id)->delete();

        return redirect()->route('perusahaan-pkl-list')->with('success', 'Siswa berhasil ditolak dari PKL');
    }
    public function siswa(User $user){
        $user->load(['pklSiswa.pembimbing', 'logbook']);

        $logbooks = $user->logbook ? $user->logbook->logbookContents()->paginate(10) : collect();
        return view('perusahaan.pkl.siswa', [
            'user' => $user,
            'logbooks' => $logbooks
        ]);
    }

    public function nilai(User $user, Request $request){
        $request->validate([
            'nilai' => 'required'
        ]);

        $user->update([
            'nilai_pkl' => $request->nilai
        ]);

        return redirect()->route('perusahaan-pkl-siswa', $user->id);
    }

    /**
     * Menghapus pelamar dari daftar PKL perusahaan (mengatur pkl_id, pkl_status, dan is_archived menjadi NULL/false).
     * Ini adalah aksi HAPUS PERMANEN dari daftar ini.
     */
    public function removeApplicant(User $user)
    {
        $perusahaanPklIds = Auth::user()->pklPerusahaan()->pluck('id');
        if (!$perusahaanPklIds->contains($user->pkl_id)) {
            return redirect()->route('perusahaan-pkl-list')
                ->with('error', 'Anda tidak diizinkan menghapus pelamar ini.');
        }

        $user->pkl_id = null;
        $user->pkl_status = null;
        $user->is_archived = false; // Pastikan tidak diarsipkan jika dihapus permanen
        $user->save();

        // Hapus logbook terkait jika ada, karena user dihapus permanen dari PKL
        Logbook::where('siswa_id', $user->id)->delete();

        return redirect()->route('perusahaan-pkl-list')->with('success', 'Pelamar berhasil dihapus dari daftar.');
    }

    /**
     * Mengarsipkan atau memulihkan pelamar dari daftar PKL perusahaan (mengatur is_archived menjadi true/false).
     * Ini adalah aksi TOGGLE ARSIP/PULIHKAN.
     */
    public function archiveApplicant(User $user, Request $request)
    {
        $perusahaanPklIds = Auth::user()->pklPerusahaan()->pluck('id');
        if (!$perusahaanPklIds->contains($user->pkl_id)) {
            return redirect()->route('perusahaan-pkl-list')
                ->with('error', 'Anda tidak diizinkan melakukan tindakan ini pada pelamar ini.');
        }

        // Tentukan apakah ini arsip atau pulihkan berdasarkan status is_archived saat ini
        $newIsArchivedStatus = !$user->is_archived; // Toggle status arsip

        $user->is_archived = $newIsArchivedStatus;
        // Penting: Jangan ubah pkl_id atau pkl_status di sini
        // karena ini adalah aksi arsip/pulihkan, bukan menolak/menghapus hubungan PKL
        $user->save();

        if ($newIsArchivedStatus) {
            return redirect()->route('perusahaan-pkl-list', ['archived' => 'true'])->with('success', 'Pelamar berhasil diarsipkan.');
        } else {
            return redirect()->route('perusahaan-pkl-list')->with('success', 'Pelamar berhasil dipulihkan dari arsip.');
        }
    }
}