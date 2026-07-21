{{-- ========================================================= --}}
{{-- 🎓 Tutorial Card --}}
{{-- ========================================================= --}}

@if(isset($tutorial))

<div class="card border-0 shadow-sm mt-3">

    <div class="card-body">

        <div class="d-flex">

            {{-- Thumbnail --}}
            <div class="me-3">

                @if($tutorial->thumbnail_url)

                    <img
                        src="{{ $tutorial->thumbnail_url }}"
                        alt="{{ $tutorial->title }}"
                        class="rounded"
                        style="width:90px;height:70px;object-fit:cover;">

                @else

                    <div
                        class="rounded bg-light d-flex align-items-center justify-content-center"
                        style="width:90px;height:70px;">

                        🎓

                    </div>

                @endif

            </div>

            {{-- Content --}}
            <div class="flex-grow-1">

                <div class="fw-bold mb-1">

                    {{ $tutorial->title }}

                </div>

                <div class="text-muted small mb-3">

                    {{ \Illuminate\Support\Str::limit(
                        $tutorial->description,
                        120
                    ) }}

                </div>

                <div class="d-flex gap-2 flex-wrap">

                    @if($tutorial->url)

                        <a
                            href="{{ $tutorial->url }}"
                            target="_blank"
                            class="btn btn-sm btn-primary">

                            ▶️ Buka Materi

                        </a>

                    @endif

                    <span class="badge bg-light text-dark">

                        {{ $tutorial->intent }}

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

@endif