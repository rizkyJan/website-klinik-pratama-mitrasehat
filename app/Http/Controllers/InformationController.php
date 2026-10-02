<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Gallery;
use App\Models\Article;
use App\Models\Promo;
use App\Models\Service;
use App\Models\Announcement;

class InformationController extends Controller
{
    public function index()
    {
        return view('information.index');
    }

    public function bpjs()
    {
        return view('information.bpjs');
    }

    public function faq()
    {
        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->get();
        return view('information.faq', compact('faqs'));
    }

    public function gallery()
    {
        $galleries = Gallery::where('is_active', true)->orderBy('sort_order')->get();
        return view('information.gallery', compact('galleries'));
    }

    public function articles()
    {
        $articles = Article::where('is_active', true)->orderBy('sort_order')->get();
        return view('information.articles', compact('articles'));
    }

    public function promo()
    {
        $promos = Promo::where('is_active', true)->orderBy('sort_order')->get();
        return view('information.promo', compact('promos'));
    }


    public function announcements()
    {
        $announcements = Announcement::visible()->get();

        return view('information.announcements', compact('announcements'));
    }

    public function legal()
    {
        return view('information.legal');
    }

    public function schedule()
    {
        $services = Service::where('is_active', true)
            ->orderBy('sort_order')
            ->with('schedules')
            ->get();

        return view('information.schedule', compact('services'));
    }
}