@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- ========================================================= --}}
    {{-- AI Module Header --}}
    {{-- ========================================================= --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body d-flex justify-content-between align-items-center">

            <div>

                <h2 class="mb-1">

                    🤖 AI Assistant

                </h2>

                <div class="text-muted">

                    Asisten cerdas untuk membantu operasional bisnis Anda.

                </div>

            </div>

            <div class="d-flex gap-2">

                <a
                    href="{{ route('admin.ai.dashboard') }}"
                    class="btn btn-outline-primary">

                    Dashboard

                </a>

                <a
                    href="{{ route('admin.ai.conversations.index') }}"
                    class="btn btn-outline-primary">

                    Percakapan

                </a>

                <a
                    href="{{ route('admin.ai.tutorials.index') }}"
                    class="btn btn-outline-primary">

                    AI Academy

                </a>

            </div>

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- Content --}}
    {{-- ========================================================= --}}
    @yield('ai-content')

</div>

@endsection