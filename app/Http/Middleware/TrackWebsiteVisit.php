<?php

namespace App\Http\Middleware;

use App\Models\WebsiteDailyVisit;
use App\Models\WebsitePageView;
use App\Models\WebsiteVisitor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrackWebsiteVisit
{
    private const COOKIE_NAME = 'clinic_visitor_id';

    private static ?bool $tablesReady = null;
    private static int $lastTableCheckAt = 0;

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldTrack($request, $response)) {
            return $response;
        }

        $visitorUuid = (string) $request->cookie(self::COOKIE_NAME, '');
        $isNewCookie = ! Str::isUuid($visitorUuid);

        if ($isNewCookie) {
            $visitorUuid = (string) Str::uuid();
        }

        try {
            $this->record($request, $visitorUuid);
        } catch (Throwable $e) {
            // Statistik tidak boleh mengganggu website publik apabila database belum siap.
            report($e);
        }

        if ($isNewCookie) {
            $response->headers->setCookie(new Cookie(
                name: self::COOKIE_NAME,
                value: $visitorUuid,
                expire: now()->addYear(),
                path: '/',
                domain: null,
                secure: $request->isSecure(),
                httpOnly: true,
                raw: false,
                sameSite: Cookie::SAMESITE_LAX,
            ));
        }

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if ($request->method() !== 'GET') {
            return false;
        }

        if ($response->getStatusCode() >= 400) {
            return false;
        }

        if ($request->expectsJson() || $request->ajax()) {
            return false;
        }

        if (auth()->check() && (bool) auth()->user()?->is_admin) {
            return false;
        }

        $routeName = (string) optional($request->route())->getName();
        if ($routeName === 'login' || str_starts_with($routeName, 'admin.')) {
            return false;
        }

        $path = trim($request->path(), '/');
        foreach (['admin', 'build', 'images', 'storage', 'favicon.ico', 'up', '.well-known'] as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                return false;
            }
        }

        $userAgent = strtolower((string) $request->userAgent());
        if ($userAgent !== '' && preg_match('/bot|crawler|spider|slurp|bingpreview|facebookexternalhit|headless|lighthouse|uptime|monitor/i', $userAgent)) {
            return false;
        }

        return $this->analyticsTablesReady();
    }

    private function analyticsTablesReady(): bool
    {
        if (self::$tablesReady === true) {
            return true;
        }

        if (self::$tablesReady === false && (time() - self::$lastTableCheckAt) < 30) {
            return false;
        }

        self::$lastTableCheckAt = time();

        try {
            self::$tablesReady = Schema::hasTable('website_visitors')
                && Schema::hasTable('website_daily_visits')
                && Schema::hasTable('website_page_views');
        } catch (Throwable) {
            self::$tablesReady = false;
        }

        return self::$tablesReady;
    }

    private function record(Request $request, string $visitorUuid): void
    {
        $now = now('Asia/Jakarta');
        $visitDate = $now->toDateString();
        $path = '/'.trim($request->path(), '/');
        if ($path === '/') {
            $path = '/';
        }

        $pageName = $this->pageName((string) optional($request->route())->getName(), $path);

        DB::transaction(function () use ($visitorUuid, $now, $visitDate, $path, $pageName) {
            $visitor = WebsiteVisitor::query()->firstOrCreate(
                ['visitor_uuid' => $visitorUuid],
                [
                    'first_seen_at' => $now,
                    'last_seen_at' => $now,
                    'total_page_views' => 0,
                ]
            );

            $visitor->forceFill(['last_seen_at' => $now])->save();
            $visitor->increment('total_page_views');

            $daily = WebsiteDailyVisit::query()->firstOrCreate(
                [
                    'visitor_id' => $visitor->id,
                    'visit_date' => $visitDate,
                ],
                [
                    'first_seen_at' => $now,
                    'last_seen_at' => $now,
                    'page_views' => 0,
                ]
            );

            $daily->forceFill(['last_seen_at' => $now])->save();
            $daily->increment('page_views');

            $page = WebsitePageView::query()->firstOrCreate(
                [
                    'visit_date' => $visitDate,
                    'path' => $path,
                ],
                [
                    'page_name' => $pageName,
                    'views' => 0,
                ]
            );

            if ($page->page_name !== $pageName) {
                $page->page_name = $pageName;
                $page->save();
            }

            $page->increment('views');
        });
    }

    private function pageName(string $routeName, string $path): string
    {
        return match ($routeName) {
            'home' => 'Beranda',
            'about' => 'Tentang Klinik',
            'services' => 'Layanan',
            'services.show' => 'Detail Layanan',
            'doctors' => 'Dokter',
            'information' => 'Informasi',
            'information.bpjs' => 'Informasi BPJS',
            'information.faq' => 'FAQ',
            'information.gallery' => 'Galeri',
            'information.articles' => 'Artikel',
            'articles.show' => 'Detail Artikel',
            'information.promo' => 'Promo',
            'information.legal' => 'Legalitas',
            'information.schedule' => 'Jadwal Pelayanan',
            'information.announcements' => 'Pengumuman',
            'information.branches' => 'Cabang',
            'information.branches.show' => 'Detail Cabang',
            'registration' => 'Pendaftaran',
            'contact' => 'Kontak',
            'feedback.index' => 'Kritik & Saran',
            default => $routeName !== '' ? Str::headline($routeName) : ($path === '/' ? 'Beranda' : Str::headline(basename($path))),
        };
    }
}
