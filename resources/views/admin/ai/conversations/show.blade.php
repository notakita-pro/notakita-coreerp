@extends('layouts.app')

@section('title', 'AI Conversation')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-xl-10">

            {{-- ========================================================= --}}
            {{-- Header --}}
            {{-- ========================================================= --}}
            <div class="card border-0 shadow-sm mb-3">

                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>

                        <h3 class="mb-1">

                            🤖 {{ $conversation->title ?: 'Percakapan AI' }}

                        </h3>

                        <div class="text-muted small">

                            Terakhir aktif
                            {{ $conversation->updated_at->diffForHumans() }}

                        </div>

                    </div>

                    <a
                        href="{{ route('admin.ai.conversations.index') }}"
                        class="btn btn-outline-secondary">

                        ← Kembali

                    </a>

                </div>

            </div>

            {{-- ========================================================= --}}
            {{-- Chat Area --}}
            {{-- ========================================================= --}}
            <div class="card border-0 shadow-sm">

                <div
                    id="chatArea"
                    class="card-body"
                    style="height:650px;overflow-y:auto;">

                    @forelse($conversation->messages as $message)

                        @if($message->role === 'user')

                            <div class="d-flex justify-content-end mb-3">

                                <div
                                    class="bg-primary text-white rounded-3 px-3 py-2"
                                    style="max-width:70%;">

                                    {!! nl2br(e($message->content)) !!}

                                    <div class="small mt-2 opacity-75">

                                        {{ $message->created_at->format('H:i') }}

                                    </div>

                                </div>

                            </div>

                        @else

                            <div class="d-flex justify-content-start mb-3">

                                <div
                                    class="bg-light rounded-3 px-3 py-2"
                                    style="max-width:70%;">

                                    {!! nl2br(e($message->content)) !!}

                                    <div class="small text-muted mt-2">

                                        {{ $message->created_at->format('H:i') }}

                                    </div>

                                </div>

                            </div>

                        @endif

                    @empty

                        <div class="text-center py-5">

                            <div style="font-size:64px">

                                🤖

                            </div>

                            <h4 class="mt-3">

                                Belum Ada Percakapan

                            </h4>

                            <p class="text-muted">

                                Conversation ini belum memiliki pesan.

                            </p>

                        </div>

                    @endforelse

                </div>

                {{-- ===================================================== --}}
                {{-- Input --}}
                {{-- ===================================================== --}}
                <div class="card-footer bg-white">

                    <form id="chatForm">

                        @csrf

                        <div class="input-group">

                            <input
                                type="text"
                                class="form-control"
                                placeholder="Ketik pesan Anda...">

                            <button
                                type="button"
                                class="btn btn-outline-secondary">

                                🎤

                            </button>

                            <button
                                class="btn btn-primary"
                                type="submit">

                                ➜

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection