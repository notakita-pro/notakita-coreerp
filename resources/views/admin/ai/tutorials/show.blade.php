@extends('layouts.app')

@section('title', $tutorial->title)

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body d-flex justify-content-between align-items-center">

            <div>

                <h2 class="mb-2">

                    🎓 {{ $tutorial->title }}

                </h2>

                <div class="text-muted">

                    Preview materi AI Academy.

                </div>

            </div>

            <div>

                <a
                    href="{{ route('admin.ai.tutorials.edit', $tutorial) }}"
                    class="btn btn-primary">

                    ✏️ Edit

                </a>

            </div>

        </div>

    </div>


    <div class="row">

        {{-- ===================================================== --}}
        {{-- Preview --}}
        {{-- ===================================================== --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm mb-4">

                @if($tutorial->thumbnail_url)

                    <img
                        src="{{ $tutorial->thumbnail_url }}"
                        class="card-img-top"
                        style="max-height:360px;object-fit:cover;">

                @endif

                <div class="card-body">

                    @if($tutorial->url)

                        <div class="ratio ratio-16x9 mb-4">

                            <iframe
                                src="{{ $tutorial->url }}"
                                allowfullscreen>

                            </iframe>

                        </div>

                    @endif

                    <h4>

                        Deskripsi

                    </h4>

                    <p class="text-muted">

                        {!! nl2br(e($tutorial->description)) !!}

                    </p>

                </div>

            </div>

        </div>

        {{-- ===================================================== --}}
        {{-- Sidebar --}}
        {{-- ===================================================== --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm mb-3">

                <div class="card-body">

                    <h5>

                        Informasi

                    </h5>

                    <hr>

                    <p>

                        <strong>Intent</strong><br>

                        <span class="badge bg-primary">

                            {{ $tutorial->intent }}

                        </span>

                    </p>

                    <p>

                        <strong>Status</strong><br>

                        @if($tutorial->is_active)

                            <span class="badge bg-success">

                                Aktif

                            </span>

                        @else

                            <span class="badge bg-secondary">

                                Draft

                            </span>

                        @endif

                    </p>

                    <p>

                        <strong>Dikirim AI</strong><br>

                        {{ number_format($tutorial->sent_count) }}

                        kali

                    </p>

                    <p>

                        <strong>Terakhir Diubah</strong><br>

                        {{ $tutorial->updated_at->diffForHumans() }}

                    </p>

                </div>

            </div>

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <a
                        href="{{ route('admin.ai.tutorials.index') }}"
                        class="btn btn-outline-secondary w-100">

                        ← Kembali

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection