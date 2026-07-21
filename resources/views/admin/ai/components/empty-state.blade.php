{{-- ========================================================= --}}
{{-- ✨ Empty State --}}
{{-- ========================================================= --}}

<div class="text-center py-5">

    <div
        class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-4"
        style="width:96px;height:96px;font-size:42px;">

        {{ $icon ?? '🤖' }}

    </div>

    <h3 class="mb-3">

        {{ $title ?? 'Belum Ada Data' }}

    </h3>

    <p
        class="text-muted mx-auto"
        style="max-width:520px;">

        {{ $description ?? 'Data belum tersedia.' }}

    </p>

    @isset($buttonText)

        <a
            href="{{ $buttonUrl }}"
            class="btn btn-primary mt-3">

            {{ $buttonText }}

        </a>

    @endisset

</div>