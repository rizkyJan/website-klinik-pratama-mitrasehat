<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Klinik Mitra Sehat</title>
    @vite(['resources/css/app.css'])

    <style>
        .admin-mobile-toggle,
        .admin-mobile-menu {
            display: none;
        }

        @media (max-width: 1099px) {
            .admin-desktop-menu,
            .admin-desktop-actions {
                display: none !important;
            }

            .admin-mobile-toggle {
                display: inline-flex;
                width: 40px;
                height: 40px;
                align-items: center;
                justify-content: center;
                border: 1px solid rgba(255,255,255,.20);
                border-radius: 10px;
                background: rgba(255,255,255,.08);
                color: #fff;
                cursor: pointer;
            }

            .admin-mobile-toggle:hover {
                background: rgba(255,255,255,.14);
            }

            .admin-mobile-menu.is-open {
                display: block;
            }

            .admin-mobile-panel {
                padding: 10px 0 14px;
                border-top: 1px solid rgba(255,255,255,.12);
            }

            .admin-mobile-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
            }

            .admin-mobile-link {
                display: flex;
                align-items: center;
                min-height: 42px;
                padding: 0 12px;
                border-radius: 9px;
                color: #fff;
                text-decoration: none;
                font-size: 13px;
                font-weight: 600;
            }

            .admin-mobile-link:hover,
            .admin-mobile-link.is-active {
                background: #154a2e;
            }

            .admin-mobile-meta {
                margin-top: 12px;
                padding-top: 12px;
                border-top: 1px solid rgba(255,255,255,.12);
                display: grid;
                gap: 9px;
            }

            .admin-mobile-meta-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                color: #d9f0e3;
                font-size: 12px;
            }

            .admin-mobile-site {
                color: #fff;
                text-decoration: none;
                font-weight: 700;
            }

            .admin-mobile-logout {
                width: 100%;
                min-height: 40px;
                border: 0;
                border-radius: 9px;
                background: #dc2626;
                color: #fff;
                font-size: 12px;
                font-weight: 700;
                cursor: pointer;
            }
        }

        @media (max-width: 520px) {
            .admin-mobile-grid {
                grid-template-columns: 1fr;
            }
        }

        .admin-ai-dropdown { position: relative; }
        .admin-ai-dropdown > button { display:inline-flex;align-items:center;gap:5px;border:0;background:transparent;color:#fff;cursor:pointer;font:inherit; }
        .admin-ai-dropdown-menu { display:none; position:absolute; top:calc(100% + 8px); left:0; min-width:245px; padding:8px; border-radius:12px; background:#fff; color:#1f2937; box-shadow:0 16px 40px rgba(15,23,42,.18); border:1px solid #e5e7eb; z-index:100; }
        .admin-ai-dropdown:hover .admin-ai-dropdown-menu, .admin-ai-dropdown:focus-within .admin-ai-dropdown-menu { display:block; }
        .admin-ai-dropdown-link { display:flex;align-items:center;justify-content:space-between;gap:10px;padding:9px 10px;border-radius:8px;color:#374151;text-decoration:none;font-size:12px;font-weight:700; }
        .admin-ai-dropdown-link:hover, .admin-ai-dropdown-link.is-active { background:#ecfdf3;color:#166534; }
        .admin-ai-badge { display:inline-flex;min-width:18px;height:18px;align-items:center;justify-content:center;padding:0 5px;border-radius:999px;background:#ef4444;color:#fff;font-size:9px;font-weight:800; }
        .admin-mobile-ai-title { grid-column:1/-1; padding:8px 12px 2px; color:#bbf7d0; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    @auth
    <nav class="bg-[#1a5d3a] text-white shadow-lg" id="adminNavbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <div class="flex items-center gap-6 min-w-0">
                    <a href="{{ route('admin.dashboard') }}" class="font-bold text-sm whitespace-nowrap">Admin Mitra Sehat</a>

                    <div class="admin-desktop-menu flex items-center gap-1 text-xs">
                        <a href="{{ route('admin.doctors.index') }}" class="px-3 py-1.5 rounded hover:bg-[#154a2e] {{ request()->routeIs('admin.doctors.*') ? 'bg-[#154a2e]' : '' }}">Dokter</a>
                        <a href="{{ route('admin.services.index') }}" class="px-3 py-1.5 rounded hover:bg-[#154a2e] {{ request()->routeIs('admin.services.*') ? 'bg-[#154a2e]' : '' }}">Layanan</a>
                        <a href="{{ route('admin.faqs.index') }}" class="px-3 py-1.5 rounded hover:bg-[#154a2e] {{ request()->routeIs('admin.faqs.*') ? 'bg-[#154a2e]' : '' }}">FAQ</a>
                        <a href="{{ route('admin.promos.index') }}" class="px-3 py-1.5 rounded hover:bg-[#154a2e] {{ request()->routeIs('admin.promos.*') ? 'bg-[#154a2e]' : '' }}">Promo</a>
                        <a href="{{ route('admin.articles.index') }}" class="px-3 py-1.5 rounded hover:bg-[#154a2e] {{ request()->routeIs('admin.articles.*') ? 'bg-[#154a2e]' : '' }}">Artikel</a>
                        <a href="{{ route('admin.galleries.index') }}" class="px-3 py-1.5 rounded hover:bg-[#154a2e] {{ request()->routeIs('admin.galleries.*') ? 'bg-[#154a2e]' : '' }}">Galeri</a>
                        <a href="{{ route('admin.branches.index') }}" class="px-3 py-1.5 rounded hover:bg-[#154a2e] {{ request()->routeIs('admin.branches.*') ? 'bg-[#154a2e]' : '' }}">Cabang</a>
                        <a href="{{ route('admin.announcements.index') }}" class="px-3 py-1.5 rounded hover:bg-[#154a2e] {{ request()->routeIs('admin.announcements.*') ? 'bg-[#154a2e]' : '' }}">Pengumuman</a>
                        <a href="{{ route('admin.feedback.index') }}" class="px-3 py-1.5 rounded hover:bg-[#154a2e] {{ request()->routeIs('admin.feedback.*') ? 'bg-[#154a2e]' : '' }}">Masukan @if(($adminUnreadFeedbackCount ?? 0) > 0) <span style="display:inline-flex;min-width:16px;height:16px;align-items:center;justify-content:center;margin-left:3px;padding:0 4px;border-radius:999px;background:#ef4444;color:#fff;font-size:9px;font-weight:800;">{{ $adminUnreadFeedbackCount }}</span>@endif</a>

                        <div class="admin-ai-dropdown">
                            <button type="button" class="px-3 py-1.5 rounded hover:bg-[#154a2e] {{ request()->routeIs('admin.ai.*') ? 'bg-[#154a2e]' : '' }}">
                                Asisten Klinik
                                @if(($adminPendingAiQuestionCount ?? 0) > 0)
                                    <span class="admin-ai-badge">{{ $adminPendingAiQuestionCount }}</span>
                                @endif
                                <span aria-hidden="true">▾</span>
                            </button>
                            <div class="admin-ai-dropdown-menu">
                                <a href="{{ route('admin.ai.knowledge.index') }}" class="admin-ai-dropdown-link {{ request()->routeIs('admin.ai.knowledge.*') ? 'is-active' : '' }}">Pengetahuan AI</a>
                                <a href="{{ route('admin.ai.unanswered.index') }}" class="admin-ai-dropdown-link {{ request()->routeIs('admin.ai.unanswered.*') ? 'is-active' : '' }}">
                                    <span>Pertanyaan Belum Terjawab</span>
                                    @if(($adminPendingAiQuestionCount ?? 0) > 0)<span class="admin-ai-badge">{{ $adminPendingAiQuestionCount }}</span>@endif
                                </a>
                                <a href="{{ route('admin.ai.history.index') }}" class="admin-ai-dropdown-link {{ request()->routeIs('admin.ai.history.*') ? 'is-active' : '' }}">Riwayat Chat</a>
                                <a href="{{ route('admin.ai.settings.edit') }}" class="admin-ai-dropdown-link {{ request()->routeIs('admin.ai.settings.*') ? 'is-active' : '' }}">Pengaturan AI</a>
                            </div>
                        </div>

                        <a href="{{ route('admin.email-settings.edit') }}" class="px-3 py-1.5 rounded hover:bg-[#154a2e] {{ request()->routeIs('admin.email-settings.*') ? 'bg-[#154a2e]' : '' }}">Email</a>
                    </div>
                </div>

                <div class="admin-desktop-actions flex items-center gap-3">
                    <a href="{{ route('home') }}" target="_blank" class="text-xs text-green-100 hover:text-white">Lihat Website</a>
                    <span class="text-xs text-green-200">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="text-xs bg-red-600 px-3 py-1.5 rounded hover:bg-red-700">Logout</button>
                    </form>
                </div>

                <button
                    type="button"
                    class="admin-mobile-toggle"
                    id="adminMobileToggle"
                    aria-label="Buka menu admin"
                    aria-expanded="false"
                    aria-controls="adminMobileMenu"
                >
                    <svg id="adminMenuOpenIcon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
                    </svg>
                    <svg id="adminMenuCloseIcon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" aria-hidden="true" style="display:none;">
                        <path stroke-linecap="round" stroke-width="2" d="M6 6l12 12M18 6L6 18"/>
                    </svg>
                </button>
            </div>

            <div class="admin-mobile-menu" id="adminMobileMenu">
                <div class="admin-mobile-panel">
                    <div class="admin-mobile-grid">
                        <a href="{{ route('admin.doctors.index') }}" class="admin-mobile-link {{ request()->routeIs('admin.doctors.*') ? 'is-active' : '' }}">Dokter</a>
                        <a href="{{ route('admin.services.index') }}" class="admin-mobile-link {{ request()->routeIs('admin.services.*') ? 'is-active' : '' }}">Layanan</a>
                        <a href="{{ route('admin.faqs.index') }}" class="admin-mobile-link {{ request()->routeIs('admin.faqs.*') ? 'is-active' : '' }}">FAQ</a>
                        <a href="{{ route('admin.promos.index') }}" class="admin-mobile-link {{ request()->routeIs('admin.promos.*') ? 'is-active' : '' }}">Promo</a>
                        <a href="{{ route('admin.articles.index') }}" class="admin-mobile-link {{ request()->routeIs('admin.articles.*') ? 'is-active' : '' }}">Artikel</a>
                        <a href="{{ route('admin.galleries.index') }}" class="admin-mobile-link {{ request()->routeIs('admin.galleries.*') ? 'is-active' : '' }}">Galeri</a>
                        <a href="{{ route('admin.branches.index') }}" class="admin-mobile-link {{ request()->routeIs('admin.branches.*') ? 'is-active' : '' }}">Cabang</a>
                        <a href="{{ route('admin.announcements.index') }}" class="admin-mobile-link {{ request()->routeIs('admin.announcements.*') ? 'is-active' : '' }}">Pengumuman</a>
                        <a href="{{ route('admin.feedback.index') }}" class="admin-mobile-link {{ request()->routeIs('admin.feedback.*') ? 'is-active' : '' }}">Masukan @if(($adminUnreadFeedbackCount ?? 0) > 0) ({{ $adminUnreadFeedbackCount }} baru) @endif</a>

                        <div class="admin-mobile-ai-title">Asisten Klinik @if(($adminPendingAiQuestionCount ?? 0) > 0) · {{ $adminPendingAiQuestionCount }} perlu dijawab @endif</div>
                        <a href="{{ route('admin.ai.knowledge.index') }}" class="admin-mobile-link {{ request()->routeIs('admin.ai.knowledge.*') ? 'is-active' : '' }}">Pengetahuan AI</a>
                        <a href="{{ route('admin.ai.unanswered.index') }}" class="admin-mobile-link {{ request()->routeIs('admin.ai.unanswered.*') ? 'is-active' : '' }}">Pertanyaan Belum Terjawab</a>
                        <a href="{{ route('admin.ai.history.index') }}" class="admin-mobile-link {{ request()->routeIs('admin.ai.history.*') ? 'is-active' : '' }}">Riwayat Chat</a>
                        <a href="{{ route('admin.ai.settings.edit') }}" class="admin-mobile-link {{ request()->routeIs('admin.ai.settings.*') ? 'is-active' : '' }}">Pengaturan AI</a>

                        <a href="{{ route('admin.email-settings.edit') }}" class="admin-mobile-link {{ request()->routeIs('admin.email-settings.*') ? 'is-active' : '' }}">Pengaturan Email</a>
                    </div>

                    <div class="admin-mobile-meta">
                        <div class="admin-mobile-meta-row">
                            <span>{{ auth()->user()->name }}</span>
                            <a href="{{ route('home') }}" target="_blank" class="admin-mobile-site">Lihat Website ↗</a>
                        </div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="admin-mobile-logout">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>

    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.getElementById('adminMobileToggle');
            const menu = document.getElementById('adminMobileMenu');
            const openIcon = document.getElementById('adminMenuOpenIcon');
            const closeIcon = document.getElementById('adminMenuCloseIcon');

            if (!button || !menu) return;

            const closeMenu = function () {
                menu.classList.remove('is-open');
                button.setAttribute('aria-expanded', 'false');
                if (openIcon) openIcon.style.display = 'block';
                if (closeIcon) closeIcon.style.display = 'none';
            };

            const openMenu = function () {
                menu.classList.add('is-open');
                button.setAttribute('aria-expanded', 'true');
                if (openIcon) openIcon.style.display = 'none';
                if (closeIcon) closeIcon.style.display = 'block';
            };

            button.addEventListener('click', function () {
                menu.classList.contains('is-open') ? closeMenu() : openMenu();
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') closeMenu();
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1100) closeMenu();
            });
        });
    </script>
    @endauth
    @stack('scripts')
</body>
</html>
