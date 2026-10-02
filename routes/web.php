<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\ArticleController as PublicArticleController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\BranchController as AdminBranchController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\EmailSettingController as AdminEmailSettingController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\AI\KnowledgeController as AdminAiKnowledgeController;
use App\Http\Controllers\Admin\AI\UnansweredController as AdminAiUnansweredController;
use App\Http\Controllers\Admin\AI\HistoryController as AdminAiHistoryController;
use App\Http\Controllers\Admin\AI\SettingController as AdminAiSettingController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// About
Route::get('/tentang', [AboutController::class, 'index'])->name('about');

// Services
Route::get('/layanan', [ServiceController::class, 'index'])->name('services');
Route::get('/layanan/{slug}', [ServiceController::class, 'show'])->name('services.show');

// Doctors
Route::get('/dokter', [DoctorController::class, 'index'])->name('doctors');

// Information
Route::prefix('/informasi')->group(function () {
    Route::get('/', [InformationController::class, 'index'])->name('information');
    Route::get('/bpjs', [InformationController::class, 'bpjs'])->name('information.bpjs');
    Route::get('/faq', [InformationController::class, 'faq'])->name('information.faq');
    Route::get('/galeri', [InformationController::class, 'gallery'])->name('information.gallery');
    Route::get('/artikel', [InformationController::class, 'articles'])->name('information.articles');
    Route::get('/artikel/{article}', [PublicArticleController::class, 'show'])->name('articles.show');
    Route::get('/promo', [InformationController::class, 'promo'])->name('information.promo');
    Route::get('/legal', [InformationController::class, 'legal'])->name('information.legal');
    Route::get('/jadwal-pelayanan', [InformationController::class, 'schedule'])->name('information.schedule');
    Route::get('/pengumuman', [InformationController::class, 'announcements'])->name('information.announcements');

    // Cabang
    Route::get('/cabang', [BranchController::class, 'index'])->name('information.branches');
    Route::get('/cabang/{branch}', [BranchController::class, 'show'])->name('information.branches.show');
});

// Registration
Route::get('/pendaftaran', [RegistrationController::class, 'index'])->name('registration');

// Contact
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');

// Kritik, Saran & Apresiasi
Route::get('/kritik-saran', [FeedbackController::class, 'index'])->name('feedback.index');
Route::post('/kritik-saran', [FeedbackController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('feedback.store');

// Asisten Klinik - chat publik (streaming)
Route::post('/asisten-klinik/chat', [AiChatController::class, 'stream'])
    ->middleware('throttle:20,1')
    ->name('ai.chat');

// ===== ADMIN =====
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

// Fallback: Laravel's auth middleware redirects to route named 'login'
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Doctors
    Route::resource('/doctors', AdminDoctorController::class)->except(['show']);
    Route::post('/doctors/{doctor}/schedules', [AdminDoctorController::class, 'storeSchedule'])->name('doctors.schedules.store');
    Route::delete('/doctors/{doctor}/schedules/{schedule}', [AdminDoctorController::class, 'destroySchedule'])->name('doctors.schedules.destroy');

    // Services
    Route::resource('/services', AdminServiceController::class)->except(['show']);

    // Faqs
    Route::resource('/faqs', AdminFaqController::class)->except(['show']);

    // Promos
    Route::resource('/promos', AdminPromoController::class)->except(['show']);

    // Articles
    Route::resource('/articles', AdminArticleController::class)->except(['show']);

    // Galleries
    Route::resource('/galleries', AdminGalleryController::class)->except(['show']);

    // Branches
    Route::resource('/branches', AdminBranchController::class)->except(['show']);

    // Pengumuman
    Route::resource('/announcements', AdminAnnouncementController::class)->except(['show']);

    // Kritik, Saran & Apresiasi
    Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/{feedback}', [AdminFeedbackController::class, 'show'])->name('feedback.show');
    Route::post('/feedback/{feedback}/reply', [AdminFeedbackController::class, 'reply'])->name('feedback.reply');
    Route::delete('/feedback/{feedback}', [AdminFeedbackController::class, 'destroy'])->name('feedback.destroy');

    // Asisten Klinik
    Route::get('/ai/knowledge', [AdminAiKnowledgeController::class, 'index'])->name('ai.knowledge.index');
    Route::get('/ai/knowledge/create', [AdminAiKnowledgeController::class, 'create'])->name('ai.knowledge.create');
    Route::post('/ai/knowledge', [AdminAiKnowledgeController::class, 'store'])->name('ai.knowledge.store');
    Route::get('/ai/knowledge/{knowledge}/edit', [AdminAiKnowledgeController::class, 'edit'])->name('ai.knowledge.edit');
    Route::put('/ai/knowledge/{knowledge}', [AdminAiKnowledgeController::class, 'update'])->name('ai.knowledge.update');
    Route::delete('/ai/knowledge/{knowledge}', [AdminAiKnowledgeController::class, 'destroy'])->name('ai.knowledge.destroy');

    Route::get('/ai/unanswered', [AdminAiUnansweredController::class, 'index'])->name('ai.unanswered.index');
    Route::get('/ai/unanswered/{unanswered}', [AdminAiUnansweredController::class, 'show'])->name('ai.unanswered.show');
    Route::post('/ai/unanswered/{unanswered}/teach', [AdminAiUnansweredController::class, 'teach'])->name('ai.unanswered.teach');
    Route::post('/ai/unanswered/{unanswered}/ignore', [AdminAiUnansweredController::class, 'ignore'])->name('ai.unanswered.ignore');
    Route::post('/ai/unanswered/{unanswered}/reopen', [AdminAiUnansweredController::class, 'reopen'])->name('ai.unanswered.reopen');

    Route::get('/ai/history', [AdminAiHistoryController::class, 'index'])->name('ai.history.index');
    Route::get('/ai/history/{conversation}', [AdminAiHistoryController::class, 'show'])->name('ai.history.show');
    Route::delete('/ai/history/{conversation}', [AdminAiHistoryController::class, 'destroy'])->name('ai.history.destroy');

    Route::get('/ai/settings', [AdminAiSettingController::class, 'edit'])->name('ai.settings.edit');
    Route::put('/ai/settings', [AdminAiSettingController::class, 'update'])->name('ai.settings.update');
    Route::post('/ai/settings/test', [AdminAiSettingController::class, 'test'])->name('ai.settings.test');

    // Pengaturan Email - Google OAuth / Gmail API
    Route::get('/email-settings', [AdminEmailSettingController::class, 'edit'])->name('email-settings.edit');
    Route::get('/email-settings/google/connect', [AdminEmailSettingController::class, 'connect'])->name('email-settings.google.connect');
    Route::get('/email-settings/google/callback', [AdminEmailSettingController::class, 'callback'])->name('email-settings.google.callback');
    Route::post('/email-settings/test', [AdminEmailSettingController::class, 'test'])->name('email-settings.test');
    Route::delete('/email-settings', [AdminEmailSettingController::class, 'disconnect'])->name('email-settings.disconnect');
});
