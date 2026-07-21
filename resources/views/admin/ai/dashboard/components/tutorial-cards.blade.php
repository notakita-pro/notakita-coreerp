{{-- ========================================================= --}}
{{-- 🎓 AI Academy --}}
{{-- ========================================================= --}}

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">

        <div>

            <h5 class="mb-1">
                🎓 AI Academy
            </h5>

            <small class="text-muted">
                Pelajari fitur CoreERP melalui panduan singkat.
            </small>

        </div>

        <a
            href="{{ route('admin.ai.tutorials.index') }}"
            class="btn btn-sm btn-outline-primary">

            Lihat Semua

        </a>

    </div>

    <div class="card-body">

        <div class="row g-3">

            @forelse($tutorials ?? [] as $tutorial)

                <div class="col-lg-4 col-md-6">

                    <div class="card h-100 border">

                        @if($tutorial->thumbnail_url)

                            <img
                                src="{{ $tutorial->thumbnail_url }}"
                                class="card-img-top"
                                alt="{{ $tutorial->title }}"
                                style="height:180px;object-fit:cover;">

                        @else

                            <div
                                class="d-flex align-items-center justify-content-center bg-light"
                                style="height:180px;font-size:48px;">

                                🎓

                            </div>

                        @endif

                        <div class="card-body d-flex flex-column">

                            <h6 class="fw-bold">

                                {{ $tutorial->title }}

                            </h6>

                            <p class="text-muted small flex-grow-1">

                                {{ \Illuminate\Support\Str::limit($tutorial->description, 90) }}

                            </p>

                            <div
                                class="d-flex justify-content-between align-items-center mt-3">

                                <small class="text-muted">

                                    {{ $tutorial->duration_label ?? '-' }}

                                </small>

                                <a
                                    href="{{ route('admin.ai.tutorials.show', $tutorial) }}"
                                    class="btn btn-sm btn-primary">

                                    Buka

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="text-center py-5 text-muted">

                        <div style="font-size:60px">

                            🎓

                        </div>

                        <h5 class="mt-3">

                            Belum ada tutorial.

                        </h5>

                        <p>

                            Tutorial yang tersedia akan muncul di sini.

                        </p>

                    </div>

                </div>

            @endforelse

        </div>

    </div>

</div>