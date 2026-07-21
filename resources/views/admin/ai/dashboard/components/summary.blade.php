{{-- ========================================================= --}}
{{-- 🤖 AI Summary --}}
{{-- ========================================================= --}}

<div class="row g-3">

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body text-center">

                <div class="fs-2 mb-2">
                    💬
                </div>

                <h4 class="mb-1">
                    {{ number_format($conversationCount ?? 0) }}
                </h4>

                <small class="text-muted">
                    Conversation
                </small>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body text-center">

                <div class="fs-2 mb-2">
                    🎤
                </div>

                <h4 class="mb-1">
                    {{ number_format($voiceCount ?? 0) }}
                </h4>

                <small class="text-muted">
                    Voice Request
                </small>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body text-center">

                <div class="fs-2 mb-2">
                    🎓
                </div>

                <h4 class="mb-1">
                    {{ number_format($tutorialCount ?? 0) }}
                </h4>

                <small class="text-muted">
                    Tutorial
                </small>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body text-center">

                <div class="fs-2 mb-2">
                    🧠
                </div>

                <h4 class="mb-1">
                    {{ number_format($tokenUsage ?? 0) }}
                </h4>

                <small class="text-muted">
                    AI Token
                </small>

            </div>

        </div>

    </div>

</div>