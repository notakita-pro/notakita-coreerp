@extends('layouts.app')

@section('title', 'AI Academy')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body d-flex justify-content-between align-items-center">

            <div>

                <h2 class="mb-1">
                    🎓 AI Academy
                </h2>

                <div class="text-muted">
                    Kelola video, PDF, gambar, dan materi pembelajaran AI.
                </div>

            </div>

            <a
                href="{{ route('admin.ai.tutorials.create') }}"
                class="btn btn-primary">

                ➕ Tutorial Baru

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Search --}}
    {{-- ========================================================= --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-2">

                    <div class="col-lg-6">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari tutorial..."
                            value="{{ request('search') }}">

                    </div>

                    <div class="col-lg-3">

                        <select
                            name="intent"
                            class="form-select">

                            <option value="">
                                Semua Intent
                            </option>

                            @foreach($intents ?? [] as $intent)

                                <option
                                    value="{{ $intent }}"
                                    @selected(request('intent') == $intent)>

                                    {{ ucfirst(str_replace('.', ' › ', $intent)) }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-lg-3">

                        <button
                            class="btn btn-outline-primary w-100">

                            🔍 Cari

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Tutorial Cards --}}
    {{-- ========================================================= --}}
    <div class="row g-4">

        @forelse($tutorials as $tutorial)

            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    @if($tutorial->thumbnail_url)

                        <img
                            src="{{ $tutorial->thumbnail_url }}"
                            class="card-img-top"
                            style="height:180px;object-fit:cover;"
                            alt="{{ $tutorial->title }}">

                    @else

                        <div
                            class="d-flex justify-content-center align-items-center bg-light"
                            style="height:180px;font-size:60px;">

                            🎓

                        </div>

                    @endif

                    <div class="card-body d-flex flex-column">

                        <h5>

                            {{ $tutorial->title }}

                        </h5>

                        <p class="text-muted small flex-grow-1">

                            {{ \Illuminate\Support\Str::limit($tutorial->description, 100) }}

                        </p>

                        <div class="mb-2">

                            <span class="badge bg-light text-dark">

                                {{ $tutorial->intent }}

                            </span>

                        </div>

                        <div
                            class="d-flex justify-content-between align-items-center mt-auto">

                            <small class="text-muted">

                                👁 {{ number_format($tutorial->sent_count) }}

                            </small>

                            <div>

                                <a
                                    href="{{ route('admin.ai.tutorials.edit', $tutorial) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    Edit

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center py-5">

                        <div style="font-size:72px">

                            🎓

                        </div>

                        <h4 class="mt-3">

                            Belum Ada Tutorial

                        </h4>

                        <p class="text-muted">

                            Klik <strong>Tutorial Baru</strong> untuk membuat materi pertama.

                        </p>

                    </div>

                </div>

            </div>

        @endforelse

    </div>


    {{-- ========================================================= --}}
    {{-- Pagination --}}
    {{-- ========================================================= --}}
    <div class="mt-4">

        {{ $tutorials->links() }}

    </div>

</div>

@endsection