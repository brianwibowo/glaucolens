<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        $role = $user->getRoleNames()->first();

        switch ($role) {
            case 'admin':
                return redirect()->route('admin-dashboard');
            case 'perusahaan':
                return redirect()->route('perusahaan-dashboard');
            case 'siswa':
                return redirect()->route('siswa-dashboard');
            case 'guru':
                return redirect()->route('guru-dashboard');
            case 'waka_kurikulum':
                return redirect()->route('waka-kurikulum-dashboard');
            case 'waka_humas':
                return redirect()->route('waka-humas-dashboard');
            case 'alumni':
                return redirect()->route('alumni-dashboard');
            case 'lsp':
                return redirect()->route('lsp-dashboard');
            case 'user':
                return redirect()->route('user');
            default:
                return redirect()->route('login'); // fallback, bisa arahkan ke 404 juga
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
