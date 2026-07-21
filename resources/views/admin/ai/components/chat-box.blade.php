{{-- ========================================================= --}}
{{-- 🤖 AI Chat Box --}}
{{-- ========================================================= --}}

<div class="card border-0 shadow-sm">

    {{-- ===================================================== --}}
    {{-- Header --}}
    {{-- ===================================================== --}}
    <div
        class="card-header bg-white d-flex justify-content-between align-items-center">

        <div>

            <strong>

                🤖 AI Assistant

            </strong>

            <div class="small text-muted">

                Siap membantu Anda.

            </div>

        </div>

        <span class="badge bg-success">

            Online

        </span>

    </div>

    {{-- ===================================================== --}}
    {{-- Messages --}}
    {{-- ===================================================== --}}
    <div
        id="chatMessages"
        class="card-body"
        style="height:600px;overflow-y:auto;">

        @isset($messages)

            @foreach($messages as $message)

                @include(
                    $message->role === 'user'
                        ? 'admin.ai.components.message-user'
                        : 'admin.ai.components.message-ai',
                    ['message' => $message]
                )

            @endforeach

        @else

            <div class="text-center py-5">

                <div style="font-size:64px">

                    🤖

                </div>

                <h4 class="mt-3">

                    Halo!

                </h4>

                <p class="text-muted">

                    Ada yang bisa saya bantu hari ini?

                </p>

            </div>

        @endisset

    </div>

    {{-- ===================================================== --}}
    {{-- Composer --}}
    {{-- ===================================================== --}}
    <div class="card-footer bg-white">

        <form
            id="chatForm"
            autocomplete="off">

            @csrf

            <div class="input-group">

                <input
                    id="messageInput"
                    type="text"
                    class="form-control"
                    placeholder="Tulis pertanyaan...">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    title="Voice">

                    🎤

                </button>

                <button
                    type="submit"
                    class="btn btn-primary">

                    Kirim

                </button>

            </div>

        </form>

    </div>

</div>