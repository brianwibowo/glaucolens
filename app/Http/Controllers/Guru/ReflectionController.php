<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Mooc;
use App\Models\MoocReflection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReflectionController extends Controller
{
    public function create(Mooc $mooc){
        return view('guru.reflection.create', [
            'mooc' => $mooc
        ]);
    }

    public function store(Request $request, Mooc $mooc){
        $request->validate([
            'reflection_text' => 'required',
        ]);

        $reflection = MoocReflection::create([
            'mooc_id' => $mooc->id,
            'reflection' => $request->reflection_text,
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('guru-mooc-show', $mooc->id);
    }
}
