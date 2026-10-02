@extends('admin.layout')
@section('title', 'Kelola Artikel')

@section('content')
@include('admin.articles._styles')

<div class="kms-article-page">
    @if(session('success'))
        <div class="kms-alert kms-alert-success">{{ session('success') }}</div>
    @endif

    <div class="kms-article-head">
        <div>
            <p class="kms-article-eyebrow">Manajemen Artikel</p>
            <h1 class="kms-article-title">Kelola Artikel</h1>
            <p class="kms-article-subtitle">Kelola artikel kesehatan yang tampil pada halaman publik klinik.</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="kms-article-add">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg>
            Tambah Artikel
        </a>
    </div>

    <div class="kms-stats">
        <div class="kms-stat"><small>Total Artikel</small><strong>{{ $articles->count() }}</strong></div>
        <div class="kms-stat"><small>Dengan Foto</small><strong>{{ $articles->filter(fn($item) => filled($item->photo))->count() }}</strong></div>
        <div class="kms-stat"><small>Kategori</small><strong>{{ $articles->pluck('category')->filter()->unique()->count() }}</strong></div>
    </div>

    <div class="kms-table-card">
        <div class="kms-table-wrap">
            <table class="kms-table">
                <thead>
                    <tr>
                        <th style="width:95px;">Foto</th>
                        <th>Artikel</th>
                        <th style="width:160px;">Kategori</th>
                        <th>Isi</th>
                        <th style="width:210px;text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        @php
                            $photoUrl = null;
                            if ($article->photo) {
                                $photoUrl = filter_var($article->photo, FILTER_VALIDATE_URL)
                                    ? $article->photo
                                    : asset($article->photo);
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="kms-thumb">
                                    @if($photoUrl)
                                        <img src="{{ $photoUrl }}" alt="{{ $article->title }}">
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <p class="kms-row-title">{{ $article->title }}</p>
                                @if(!empty($article->slug))
                                    <p class="kms-row-slug">/{{ $article->slug }}</p>
                                @endif
                            </td>
                            <td><span class="kms-chip">{{ $article->category ?: 'Tanpa kategori' }}</span></td>
                            <td><div class="kms-content-preview">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 95) }}</div></td>
                            <td>
                                <div class="kms-actions-inline">
                                    <a class="kms-link kms-link-view" href="{{ route('articles.show', $article) }}" target="_blank">Lihat Publik</a>
                                    <a class="kms-link kms-link-edit" href="{{ route('admin.articles.edit', $article) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" onsubmit="return confirm('Hapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="kms-link kms-link-delete" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center;padding:38px;color:#8b958f;">Belum ada artikel.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
