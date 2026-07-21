{{-- ========================================================= --}}
{{-- 💬 Recent Conversations --}}
{{-- ========================================================= --}}

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">

        <div>

            <h5 class="mb-1">
                💬 Percakapan Terbaru
            </h5>

            <small class="text-muted">
                Lanjutkan percakapan sebelumnya.
            </small>

        </div>

        <a
            href="{{ route('admin.ai.conversations.index') }}"
            class="btn btn-sm btn-outline-primary">

            Lihat Semua

        </a>

    </div>

    <div class="list-group list-group-flush">

        @forelse($recentConversations ?? [] as $conversation)

            <a
                href="{{ route('admin.ai.conversations.show', $conversation) }}"
                class="list-group-item list-group-item-action">

                <div class="fw-semibold">

                    {{ $conversation->title ?: 'Percakapan Baru' }}

                </div>

                @if($conversation->summary)

                    <small class="text-muted">

                        {{ \Illuminate\Support\Str::limit($conversation->summary, 80) }}

                    </small>

                @endif

                <div class="small text-muted mt-1">

                    {{ $conversation->updated_at->diffForHumans() }}

                </div>

            </a>

        @empty

            <div class="p-4 text-center text-muted">

                <div class="fs-1 mb-2">
                    💬
                </div>

                <div class="fw-semibold">
                    Belum ada percakapan.
                </div>

                <small>
                    Mulailah bertanya kepada AI untuk membuat percakapan pertama.
                </small>

            </div>

        @endforelse

    </div>

</div>