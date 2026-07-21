@extends('layouts.app')

@section('title', 'AI Conversations')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body d-flex justify-content-between align-items-center">

            <div>

                <h2 class="mb-1">

                    💬 AI Conversations

                </h2>

                <div class="text-muted">

                    Riwayat percakapan bersama AI Assistant.

                </div>

            </div>

            <a
                href="{{ route('admin.ai.chat') }}"
                class="btn btn-primary">

                🤖 Chat Baru

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Search --}}
    {{-- ========================================================= --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="input-group">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari percakapan..."
                        value="{{ request('search') }}">

                    <button
                        class="btn btn-outline-primary">

                        Cari

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Conversation List --}}
    {{-- ========================================================= --}}
    <div class="card border-0 shadow-sm">

        <div class="list-group list-group-flush">

            @forelse($conversations as $conversation)

                <a
                    href="{{ route('admin.ai.conversations.show', $conversation) }}"
                    class="list-group-item list-group-item-action py-3">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="fw-semibold">

                                {{ $conversation->title ?: 'Percakapan Baru' }}

                            </div>

                            <div class="text-muted small mt-1">

                                {{ \Illuminate\Support\Str::limit($conversation->summary, 120) }}

                            </div>

                        </div>

                        <div class="text-end">

                            @if($conversation->status == 'active')

                                <span class="badge bg-success">

                                    Aktif

                                </span>

                            @else

                                <span class="badge bg-secondary">

                                    Ditutup

                                </span>

                            @endif

                            <div class="small text-muted mt-2">

                                {{ $conversation->updated_at->diffForHumans() }}

                            </div>

                        </div>

                    </div>

                </a>

            @empty

                <div class="text-center py-5">

                    <div style="font-size:64px">

                        💬

                    </div>

                    <h4 class="mt-3">

                        Belum Ada Percakapan

                    </h4>

                    <p class="text-muted">

                        Mulailah berbicara dengan AI untuk membuat percakapan pertama.

                    </p>

                </div>

            @endforelse

        </div>

    </div>


    <div class="mt-4">

        {{ $conversations->links() }}

    </div>

</div>

@endsection