<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ClinicSetting;
use App\Models\Announcement;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->orderBy('sort_order')->take(6)->get();
        $clinicName = ClinicSetting::get('clinic_name', 'Klinik Pratama Mitra Sehat');
        $clinicTagline = ClinicSetting::get('clinic_tagline', 'Mitra Tepat Menuju Sehat');
        $announcements = Announcement::visible()->take(3)->get();

        return view('home', compact('services', 'clinicName', 'clinicTagline', 'announcements'));
    }
}