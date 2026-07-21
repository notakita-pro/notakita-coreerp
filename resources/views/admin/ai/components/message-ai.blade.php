{{-- ========================================================= --}}
{{-- 🤖 AI Message --}}
{{-- ========================================================= --}}

<div class="d-flex justify-content-start mb-4">

    {{-- Avatar --}}
    <div class="me-3">

        <div
            class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
            style="width:42px;height:42px;">

            🤖

        </div>

    </div>

    {{-- Message --}}
    <div
        class="bg-light rounded-4 px-3 py-3 shadow-sm"
        style="max-width:80%;">

        {{-- AI Name --}}
        <div class="fw-semibold mb-2">

            NotaKita AI

        </div>

        {{-- Content --}}
        <div class="mb-2">

            {!! nl2br(e($message->content)) !!}

        </div>

        {{-- Footer --}}
        <div
            class="d-flex justify-content-between align-items-center mt-3">

            <small class="text-muted">

                {{ $message->created_at->format('H:i') }}

            </small>

            <div class="d-flex gap-2">

                <button
                    type="button"
                    class="btn btn-sm btn-light"
                    title="Copy">

                    📋

                </button>

                <button
                    type="button"
                    class="btn btn-sm btn-light"
                    title="Play Voice">

                    🔊

                </button>

            </div>

        </div>

    </div>

</div>