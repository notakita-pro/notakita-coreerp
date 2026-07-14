<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">

<style>

body{
    font-family: DejaVu Sans, sans-serif;
    font-size:12px;
    color:#222;
}

h2{
    margin:0;
    text-align:center;
}

.subtitle{
    text-align:center;
    margin-bottom:20px;
    font-size:13px;
}

.info{
    margin-bottom:20px;
}

.info table{
    width:100%;
}

.info td{
    padding:3px 0;
}

.summary{
    width:100%;
    border-collapse:collapse;
    margin-bottom:20px;
}

.summary td{
    border:1px solid #ccc;
    padding:8px;
}

.summary td:first-child{
    width:180px;
    font-weight:bold;
    background:#f5f5f5;
}

.report{
    width:100%;
    border-collapse:collapse;
}

.report th{
    background:#2563eb;
    color:#fff;
    border:1px solid #000;
    padding:8px;
    font-size:11px;
}

.report td{
    border:1px solid #999;
    padding:6px;
    font-size:10px;
}

.right{
    text-align:right;
}

.center{
    text-align:center;
}

.footer{
    margin-top:30px;
    text-align:right;
    font-size:11px;
    color:#666;
}

</style>

</head>
<body>

<h2>LAPORAN PEMBELIAN</h2>

<div class="subtitle">

<b>{{ $company->name }}</b><br>

Periode :
{{ \Carbon\Carbon::parse($from)->format('d-m-Y') }}
s/d
{{ \Carbon\Carbon::parse($to)->format('d-m-Y') }}

</div>

<div class="info">

<table>

<tr>
<td width="180">Perusahaan</td>
<td>: {{ $company->name }}</td>
</tr>

<tr>
<td>Dicetak</td>
<td>: {{ now()->format('d-m-Y H:i') }}</td>
</tr>

</table>

</div>

<table class="summary">

<tr>
<td>Jumlah Nota</td>
<td>{{ number_format($summary['transactions']) }}</td>
</tr>

<tr>
<td>Supplier</td>
<td>{{ number_format($summary['suppliers']) }}</td>
</tr>

<tr>
<td>Total Item</td>
<td>{{ number_format($summary['items']) }}</td>
</tr>

<tr>
<td>Total Belanja</td>
<td>
Rp {{ number_format($summary['total'],0,',','.') }}
</td>
</tr>

</table>

<table class="report">

<thead>

<tr>

<th>Tanggal</th>
<th>No Nota</th>
<th>Supplier</th>
<th>Barang</th>
<th>Satuan</th>
<th>Qty</th>
<th>Harga</th>
<th>Subtotal</th>

</tr>

</thead>

<tbody>

@foreach($rows as $row)

<tr>

<td class="center">
{{ optional($row->purchase?->invoice_date)->format('d-m-Y') }}
</td>

<td>
{{ $row->purchase?->invoice_number }}
</td>

<td>
{{ $row->purchase?->supplier?->name }}
</td>

<td>
{{ $row->item?->name }}
</td>

<td class="center">
{{ $row->item?->unit }}
</td>

<td class="center">
{{ number_format($row->qty,0,',','.') }}
</td>

<td class="right">
Rp {{ number_format($row->unit_price,0,',','.') }}
</td>

<td class="right">
Rp {{ number_format($row->total_price,0,',','.') }}
</td>

</tr>

@endforeach

</tbody>

</table>

<div class="footer">

Dicetak pada {{ now()->format('d-m-Y H:i') }}

</div>

</body>
</html>