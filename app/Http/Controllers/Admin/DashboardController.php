<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiUnansweredQuestion;
use App\Models\Announcement;
use App\Models\Article;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\Faq;
use App\Models\Feedback;
use App\Models\Gallery;
use App\Models\Promo;
use App\Models\Service;
use App\Models\WebsiteDailyVisit;
use App\Models\WebsitePageView;
use App\Models\WebsiteVisitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $timezone = 'Asia/Jakarta';
        $today = now($timezone)->startOfDay();
        $yesterday = $today->copy()->subDay();
        $range = $this->normalizeRange($request->string('range', '7')->toString());

        $contentStats = [
            'doctors' => Doctor::count(),
            'services' => Service::count(),
            'faqs' => Faq::count(),
            'promos' => Promo::count(),
            'articles' => Article::count(),
            'galleries' => Gallery::count(),
            'branches' => Branch::count(),
        ];

        $analyticsReady = Schema::hasTable('website_visitors')
            && Schema::hasTable('website_daily_visits')
            && Schema::hasTable('website_page_views');

        $analytics = [
            'visitors_today' => 0,
            'visitors_total' => 0,
            'page_views_today' => 0,
            'page_views_total' => 0,
            'visitors_yesterday' => 0,
            'visitor_change' => null,
        ];

        $chart = [
            'labels' => [],
            'visitors' => [],
            'page_views' => [],
            'max' => 1,
            'title' => '7 hari terakhir',
        ];

        $topPages = collect();

        if ($analyticsReady) {
            $analytics['visitors_today'] = WebsiteDailyVisit::query()->whereDate('visit_date', $today)->count();
            $analytics['visitors_yesterday'] = WebsiteDailyVisit::query()->whereDate('visit_date', $yesterday)->count();
            $analytics['visitors_total'] = WebsiteVisitor::query()->count();
            $analytics['page_views_today'] = (int) WebsiteDailyVisit::query()->whereDate('visit_date', $today)->sum('page_views');
            $analytics['page_views_total'] = (int) WebsiteVisitor::query()->sum('total_page_views');
            $analytics['visitor_change'] = $this->percentageChange(
                $analytics['visitors_today'],
                $analytics['visitors_yesterday']
            );

            [$chart, $rangeStart] = $this->buildChart($range, $today);

            $topPages = WebsitePageView::query()
                ->select('path', 'page_name')
                ->selectRaw('SUM(views) as total_views')
                ->whereDate('visit_date', '>=', $rangeStart)
                ->whereDate('visit_date', '<=', $today)
                ->groupBy('path', 'page_name')
                ->orderByDesc('total_views')
                ->limit(6)
                ->get();
        }

        $quickStats = [
            'new_feedback' => Schema::hasTable('feedbacks') ? Feedback::where('status', 'new')->count() : 0,
            'pending_ai' => Schema::hasTable('ai_unanswered_questions') ? AiUnansweredQuestion::where('status', 'pending')->count() : 0,
            'active_announcements' => Schema::hasTable('announcements') ? Announcement::visible()->count() : 0,
            'doctors' => $contentStats['doctors'],
        ];

        $activities = $this->recentActivities();

        return view('admin.dashboard', compact(
            'contentStats',
            'analyticsReady',
            'analytics',
            'chart',
            'topPages',
            'quickStats',
            'activities',
            'range',
            'today',
        ));
    }

    private function normalizeRange(string $range): string
    {
        return in_array($range, ['7', '30', 'year'], true) ? $range : '7';
    }

    private function buildChart(string $range, Carbon $today): array
    {
        if ($range === 'year') {
            $start = $today->copy()->startOfYear();
            $rows = WebsiteDailyVisit::query()
                ->whereDate('visit_date', '>=', $start)
                ->whereDate('visit_date', '<=', $today)
                ->get(['visitor_id', 'visit_date', 'page_views']);

            $grouped = $rows->groupBy(fn ($row) => Carbon::parse($row->visit_date)->format('Y-m'));
            $labels = [];
            $visitors = [];
            $pageViews = [];

            for ($month = 1; $month <= 12; $month++) {
                $date = $today->copy()->month($month)->startOfMonth();
                if ($date->greaterThan($today)) {
                    break;
                }
                $key = $date->format('Y-m');
                $items = $grouped->get($key, collect());
                $labels[] = $date->translatedFormat('M');
                $visitors[] = $items->pluck('visitor_id')->unique()->count();
                $pageViews[] = (int) $items->sum('page_views');
            }

            return [[
                'labels' => $labels,
                'visitors' => $visitors,
                'page_views' => $pageViews,
                'max' => max(1, ...$visitors, ...$pageViews),
                'title' => 'Tahun '.$today->year,
            ], $start];
        }

        $days = $range === '30' ? 30 : 7;
        $start = $today->copy()->subDays($days - 1);
        $rows = WebsiteDailyVisit::query()
            ->select('visit_date')
            ->selectRaw('COUNT(*) as visitors')
            ->selectRaw('SUM(page_views) as page_views')
            ->whereDate('visit_date', '>=', $start)
            ->whereDate('visit_date', '<=', $today)
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->get()
            ->keyBy(fn ($row) => Carbon::parse($row->visit_date)->toDateString());

        $labels = [];
        $visitors = [];
        $pageViews = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $key = $date->toDateString();
            $row = $rows->get($key);
            $labels[] = $days === 7 ? $date->translatedFormat('D') : $date->format('d M');
            $visitors[] = (int) ($row->visitors ?? 0);
            $pageViews[] = (int) ($row->page_views ?? 0);
        }

        return [[
            'labels' => $labels,
            'visitors' => $visitors,
            'page_views' => $pageViews,
            'max' => max(1, ...$visitors, ...$pageViews),
            'title' => $days === 7 ? '7 hari terakhir' : '30 hari terakhir',
        ], $start];
    }

    private function percentageChange(int $today, int $yesterday): ?float
    {
        if ($yesterday === 0) {
            return $today === 0 ? 0.0 : null;
        }

        return round((($today - $yesterday) / $yesterday) * 100, 1);
    }

    private function recentActivities(): Collection
    {
        $items = collect();

        if (Schema::hasTable('feedbacks')) {
            Feedback::query()->latest()->limit(4)->get()->each(function (Feedback $feedback) use ($items) {
                $items->push([
                    'type' => 'feedback',
                    'title' => $feedback->type_label.' dari '.$feedback->name,
                    'description' => $feedback->subject ?: 'Masukan baru diterima',
                    'time' => $feedback->created_at,
                    'url' => route('admin.feedback.show', $feedback),
                ]);
            });
        }

        if (Schema::hasTable('ai_unanswered_questions')) {
            AiUnansweredQuestion::query()->where('status', 'pending')->latest('last_asked_at')->limit(4)->get()->each(function ($question) use ($items) {
                $items->push([
                    'type' => 'ai',
                    'title' => 'AI belum bisa menjawab',
                    'description' => str($question->last_question ?: $question->question)->limit(75),
                    'time' => $question->last_asked_at ?: $question->updated_at,
                    'url' => route('admin.ai.unanswered.show', $question),
                ]);
            });
        }

        if (Schema::hasTable('announcements')) {
            Announcement::query()->latest()->limit(3)->get()->each(function (Announcement $announcement) use ($items) {
                $items->push([
                    'type' => 'announcement',
                    'title' => 'Pengumuman diperbarui',
                    'description' => str($announcement->title)->limit(75),
                    'time' => $announcement->updated_at,
                    'url' => route('admin.announcements.edit', $announcement),
                ]);
            });
        }

        return $items
            ->filter(fn ($item) => $item['time'])
            ->sortByDesc(fn ($item) => $item['time'])
            ->take(7)
            ->values();
    }
}
