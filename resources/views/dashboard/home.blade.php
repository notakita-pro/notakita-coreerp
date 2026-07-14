@extends('layouts.app')

@push('styles')
    {{-- CSS dashboard --}}
@endpush

@section('content')
@if(!empty($company))

<div class="dashboard-home">

    @include('dashboard.components.company-card')

    @include('dashboard.components.wallet-card')

    @include('dashboard.components.quick-menu')

    @include('dashboard.components.latest-transaction')

    @include('dashboard.components.ai-card')

</div>

@endif
@endsection