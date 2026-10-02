<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\ClinicSetting;

class AboutController extends Controller
{
    public function index()
    {
        $facilities = Facility::where('is_active', true)->orderBy('sort_order')->get();
        $profile = ClinicSetting::get('about_profile');
        $history = ClinicSetting::get('about_history');
        $vision = ClinicSetting::get('vision');
        $mission = ClinicSetting::get('mission');

        return view('about', compact('facilities', 'profile', 'history', 'vision', 'mission'));
    }
}