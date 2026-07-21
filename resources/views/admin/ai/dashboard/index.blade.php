@extends('layouts.app')

@section('title', 'AI Assistant')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-xl-10">

            {{-- ========================================================= --}}
            {{-- Header --}}
            {{-- ========================================================= --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>

                        <h2 class="mb-1">
                            🤖 AI Assistant
                        </h2>

                        <div class="text-muted">
                            Siap membantu bisnis Anda kapan saja.
                        </div>

                    </div>

                    <a
                        href="{{ route('admin.ai.conversations.index') }}"
                        class="btn btn-outline-primary">

                        💬 Riwayat

                    </a>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- Quick Question --}}
            {{-- ========================================================= --}}
            <div class="card border-0 shadow-sm mb-3">

                <div class="card-body">

                    <div class="mb-2 fw-semibold">

                        ⚡ Pertanyaan Cepat

                    </div>

                    <div class="d-flex flex-wrap gap-2">

                        <button class="btn btn-light ai-question">
                            💰 Posisi Kas
                        </button>

                        <button class="btn btn-light ai-question">
                            📈 Penjualan Hari Ini
                        </button>

                        <button class="btn btn-light ai-question">
                            📦 Barang Hampir Habis
                        </button>

                        <button class="btn btn-light ai-question">
                            📊 Laporan Bulan Ini
                        </button>

                        <button class="btn btn-light ai-question">
                            🎓 Cara Menggunakan Sales
                        </button>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- Chat Area --}}
            {{-- ========================================================= --}}
            <div class="card border-0 shadow-sm">

                <div
                    id="chatArea"
                    class="card-body"
                    style="height:500px;overflow-y:auto;">

                    <div class="text-center text-muted mt-5">

                        <div style="font-size:60px">

                            🤖

                        </div>

                        <h4 class="mt-3">

                            Halo...

                        </h4>

                        <p>

                            Saya siap membantu mengelola bisnis Anda.

                        </p>

                        <p class="small">

                            Silakan ketik pertanyaan di bawah.

                        </p>

                    </div>

                </div>

                <div class="card-footer bg-white">

                    <form id="aiChatForm">

                        @csrf

                        <div class="input-group">

                            <input
                                id="message"
                                type="text"
                                class="form-control"
                                placeholder="Tanyakan apa saja tentang bisnis Anda...">

                            <button
                                type="button"
                                class="btn btn-outline-secondary">

                                🎤

                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary">

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