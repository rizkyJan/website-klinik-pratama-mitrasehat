<?php

namespace App\Http\Controllers;

use App\Models\Doctor;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::where('is_active', true)
            ->orderBy('specialization')
            ->orderBy('sort_order')
            ->with('schedules')
            ->get();

        // Group by specialization
        $groupedDoctors = $doctors->groupBy('specialization');

        return view('doctors.index', compact('groupedDoctors'));
    }
}
