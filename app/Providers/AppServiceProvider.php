<?php

namespace App\Providers;

use App\Models\Feedback;
use App\Models\AiUnansweredQuestion;
use App\Models\AiSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('admin.layout', function ($view) {
            $unreadFeedbackCount = 0;
            $pendingAiQuestionCount = 0;

            try {
                if (Schema::hasTable('feedbacks')) {
                    $unreadFeedbackCount = Feedback::where('status', 'new')->count();
                }

                if (Schema::hasTable('ai_unanswered_questions')) {
                    $pendingAiQuestionCount = AiUnansweredQuestion::where('status', 'pending')->count();
                }
            } catch (Throwable) {
                // Jangan mengganggu halaman admin apabila database belum dimigrasikan.
            }

            $view->with([
                'adminUnreadFeedbackCount' => $unreadFeedbackCount,
                'adminPendingAiQuestionCount' => $pendingAiQuestionCount,
            ]);
        });

        View::composer('partials.ai-chat', function ($view) {
            $settings = [
                'enabled' => true,
                'assistant_name' => 'Asisten Klinik Mitra Sehat',
                'welcome_message' => 'Halo! Saya Asisten Klinik Mitra Sehat. Saya dapat membantu informasi layanan klinik dan informasi kesehatan umum. Ada yang bisa saya bantu?',
            ];

            try {
                if (Schema::hasTable('ai_settings')) {
                    $settings = [
                        'enabled' => AiSetting::bool('enabled', true),
                        'assistant_name' => AiSetting::valueOf('assistant_name', $settings['assistant_name']),
                        'welcome_message' => AiSetting::valueOf('welcome_message', $settings['welcome_message']),
                    ];
                }
            } catch (Throwable) {
                // Tetap tampil menggunakan nilai default ketika migrasi AI belum dijalankan.
            }

            $view->with('aiWidgetSettings', $settings);
        });

    }
}
