@extends('layouts.app')

@section('title', 'Tutorial Baru')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h2 class="mb-2">
                🎓 Tutorial Baru
            </h2>

            <p class="text-muted mb-0">
                Tambahkan materi pembelajaran baru yang dapat digunakan AI
                untuk membantu pengguna memahami fitur CoreERP.
            </p>

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- Form --}}
    {{-- ========================================================= --}}
    <form
        action="{{ route('admin.ai.tutorials.store') }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="row g-4">

                    {{-- Judul --}}
                    <div class="col-lg-8">

                        <label class="form-label">

                            Judul Tutorial

                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}"
                            required>

                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Intent --}}
                    <div class="col-lg-4">

                        <label class="form-label">

                            Intent

                        </label>

                        <input
                            type="text"
                            name="intent"
                            class="form-control @error('intent') is-invalid @enderror"
                            value="{{ old('intent') }}"
                            placeholder="sales.create"
                            required>

                        @error('intent')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">

                        <label class="form-label">

                            Deskripsi

                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- URL --}}
                    <div class="col-lg-6">

                        <label class="form-label">

                            URL Materi

                        </label>

                        <input
                            type="url"
                            name="url"
                            class="form-control"
                            value="{{ old('url') }}"
                            placeholder="https://">

                    </div>

                    {{-- Thumbnail --}}
                    <div class="col-lg-6">

                        <label class="form-label">

                            Thumbnail

                        </label>

                        <input
                            type="file"
                            name="thumbnail"
                            class="form-control">

                    </div>

                    {{-- Status --}}
                    <div class="col-lg-4">

                        <label class="form-label">

                            Status

                        </label>

                        <select
                            name="is_active"
                            class="form-select">

                            <option value="1">
                                Aktif
                            </option>

                            <option value="0">
                                Draft
                            </option>

                        </select>

                    </div>

                </div>

            </div>

            <div class="card-footer bg-white d-flex justify-content-between">

                <a
                    href="{{ route('admin.ai.tutorials.index') }}"
                    class="btn btn-outline-secondary">

                    ← Kembali

                </a>

                <button
                    class="btn btn-primary"
                    type="submit">

                    💾 Simpan Tutorial

                </button>

            </div>

        </div>

    </form>

</div>

@endsection