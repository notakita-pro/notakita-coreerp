{{-- ========================================================= --}}
{{-- 🔊 Voice Player --}}
{{-- ========================================================= --}}

@if(!empty($url))

<div class="card border-0 bg-light">

    <div class="card-body py-2 px-3">

        <div class="d-flex align-items-center">

            {{-- Icon --}}
            <div class="me-3">

                <div
                    class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                    style="width:44px;height:44px;">

                    🔊

                </div>

            </div>

            {{-- Content --}}
            <div class="flex-grow-1">

                <div class="fw-semibold">

                    Voice AI

                </div>

                <div class="small text-muted">

                    Dengarkan penjelasan AI.

                </div>

            </div>

        </div>

        <div class="mt-3">

            <audio
                controls
                preload="none"
                class="w-100">

                <source
                    src="{{ $url }}"
                    type="audio/mpeg">

                Browser Anda tidak mendukung audio.

            </audio>

        </div>

    </div>

</div>

@endif