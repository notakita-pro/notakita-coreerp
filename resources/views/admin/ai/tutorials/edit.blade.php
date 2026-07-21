@extends('layouts.app')

@section('title', 'Edit Tutorial')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body d-flex justify-content-between align-items-center">

            <div>

                <h2 class="mb-2">

                    ✏️ Edit Tutorial

                </h2>

                <p class="text-muted mb-0">

                    Perbarui materi pembelajaran AI Academy.

                </p>

            </div>

            <small class="text-muted">

                Terakhir diperbarui
                {{ $tutorial->updated_at->diffForHumans() }}

            </small>

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- Form --}}
    {{-- ========================================================= --}}
    <form
        action="{{ route('admin.ai.tutorials.update', $tutorial) }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="row g-4">

                    {{-- ================================================= --}}
                    {{-- Judul --}}
                    {{-- ================================================= --}}
                    <div class="col-lg-8">

                        <label class="form-label">

                            Judul Tutorial

                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $tutorial->title) }}"
                            required>

                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- ================================================= --}}
                    {{-- Intent --}}
                    {{-- ================================================= --}}
                    <div class="col-lg-4">

                        <label class="form-label">

                            Intent

                        </label>

                        <select
                            name="intent"
                            class="form-select @error('intent') is-invalid @enderror"
                            required>

                            @foreach($intents ?? [] as $intent)

                                <option
                                    value="{{ $intent }}"
                                    @selected(old('intent', $tutorial->intent) == $intent)>

                                    {{ ucfirst(str_replace('.', ' › ', $intent)) }}

                                </option>

                            @endforeach

                        </select>

                        @error('intent')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- ================================================= --}}
                    {{-- Deskripsi --}}
                    {{-- ================================================= --}}
                    <div class="col-12">

                        <label class="form-label">

                            Deskripsi

                        </label>

                        <textarea
                            name="description"
                            rows="5"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $tutorial->description) }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- ================================================= --}}
                    {{-- URL --}}
                    {{-- ================================================= --}}
                    <div class="col-lg-6">

                        <label class="form-label">

                            URL Materi

                        </label>

                        <input
                            type="url"
                            name="url"
                            class="form-control"
                            value="{{ old('url', $tutorial->url) }}">

                    </div>

                    {{-- ================================================= --}}
                    {{-- Thumbnail --}}
                    {{-- ================================================= --}}
                    <div class="col-lg-6">

                        <label class="form-label">

                            Thumbnail Baru

                        </label>

                        <input
                            type="file"
                            name="thumbnail"
                            class="form-control">

                    </div>

                    @if($tutorial->thumbnail_url)

                        <div class="col-lg-6">

                            <label class="form-label">

                                Thumbnail Saat Ini

                            </label>

                            <div>

                                <img
                                    src="{{ $tutorial->thumbnail_url }}"
                                    class="img-fluid rounded border"
                                    style="max-height:180px;">

                            </div>

                        </div>

                    @endif

                    {{-- ================================================= --}}
                    {{-- Status --}}
                    {{-- ================================================= --}}
                    <div class="col-lg-4">

                        <label class="form-label">

                            Status

                        </label>

                        <select
                            name="is_active"
                            class="form-select">

                            <option
                                value="1"
                                @selected(old('is_active', $tutorial->is_active))>

                                Aktif

                            </option>

                            <option
                                value="0"
                                @selected(! old('is_active', $tutorial->is_active))>

                                Draft

                            </option>

                        </select>

                    </div>

                </div>

            </div>

            <div class="card-footer bg-white d-flex justify-content-between align-items-center">

                <a
                    href="{{ route('admin.ai.tutorials.index') }}"
                    class="btn btn-outline-secondary">

                    ← Kembali

                </a>

                <div>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        💾 Simpan Perubahan

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection