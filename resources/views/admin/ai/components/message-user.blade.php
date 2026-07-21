{{-- ========================================================= --}}
{{-- 👤 User Message --}}
{{-- ========================================================= --}}

<div class="d-flex justify-content-end mb-3">

    <div
        class="bg-primary text-white rounded-4 px-3 py-2 shadow-sm"
        style="max-width:75%;">

        {{-- Message --}}
        <div class="mb-1">

            {!! nl2br(e($message->content)) !!}

        </div>

        {{-- Footer --}}
        <div
            class="d-flex justify-content-end align-items-center small opacity-75">

            <span>

                {{ $message->created_at->format('H:i') }}

            </span>

        </div>

    </div>

</div>