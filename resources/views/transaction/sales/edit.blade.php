@extends('layouts.app')

@section('content')

<div class="container">

    <div class="page-header">

        <div>

            <h2 class="page-title">
                Edit Penjualan
            </h2>

            <p class="page-description">
                Perbarui informasi transaksi penjualan.
            </p>

        </div>

    </div>

    <hr class="page-divider">

    <div class="card">

        <form method="POST" action="#">

            @csrf

            @method('PUT')

            <div class="form-group">

                <label>Tanggal Invoice</label>

                <input
                    type="date"
                    name="invoice_date"
                    value="{{ old('invoice_date', optional($sale ?? null)->invoice_date) }}">

            </div>

            <div class="form-group">

                <label>Customer</label>

                <select
                    name="customer_id">

                    <option value="">
                        -- Pilih Customer --
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Catatan</label>

                <textarea
                    name="notes"
                    rows="4">{{ old('notes', optional($sale ?? null)->notes) }}</textarea>

            </div>

            <div class="button-group">

                <button
                    class="button btn-green"
                    type="submit">

                    Update

                </button>

                <a
                    href="{{ url()->previous() }}"
                    class="button btn-blue">

                    Batal

                </a>

            </div>

        </form>

    </div>

</div>

<style>

.page-divider{

    border:0;

    border-top:1px solid var(--border-color);

    margin:20px 0;

}

.card{

    max-width:720px;

    margin:auto;

    background:#fff;

    border-radius:15px;

    padding:25px;

    border:1px solid var(--border-color);

    box-shadow:var(--shadow-sm);

}

.form-group{

    margin-bottom:18px;

}

.form-group label{

    display:block;

    margin-bottom:8px;

    font-weight:600;

}

.form-group input,
.form-group select,
.form-group textarea{

    width:100%;

    padding:10px 12px;

}

.button-group{

    display:flex;

    gap:10px;

    margin-top:25px;

}

</style>

@endsection