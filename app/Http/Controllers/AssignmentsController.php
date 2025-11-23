<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use function Laravel\Prompts\error;

class AssignmentsController extends Controller
{
    public function create(){
        return view('AddAssignment');
    }
    public function index()
    {
        $assignments = Assignment::where('user_id', Auth::id())
            ->get();
        return view('dashboard', [
            'assignments' => $assignments
        ]);
    }

    public function search(Request $request)
    {
//        $assignments = Assignment::where('user_id', Auth::id())
//            ->where('judul', 'like', "%$request->keyword%")
//            ->orwhere('topik', 'like', "%$request->keyword%")
//            ->get();
        $query = Assignment::where('user_id', Auth::id());
        if ($request->has('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                    ->orWhere('category', 'like', '%' . $request->keyword . '%');
            });
        }
        $assignments = $query->get();
        if ($assignments->isEmpty()) {
            return redirect()->route('dashboard')
                ->with('errorsearch', 'Tidak ada data yang ditemukan');
        }
        return view('dashboard', [
            'assignments' => $assignments
        ]);
    }

    public function store(\App\Http\Requests\Assignment\StoreAssignment $request){
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        \App\Models\Assignment::create($validated);
        return redirect()->route('dashboard')->with('success', 'Berhasil menambahkan data');
    }
}
