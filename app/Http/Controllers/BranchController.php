<?php

namespace App\Http\Controllers;

use App\Models\Branch;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('id')->get();

        return view('information.branches', compact('branches'));
    }

    public function show(Branch $branch)
    {
        return view('information.branch-detail', compact('branch'));
    }
}
