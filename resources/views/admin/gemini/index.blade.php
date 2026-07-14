<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>AI Usage Monitor</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial,Helvetica,sans-serif;
        }

        body{
            background:#f4f6f9;
            color:#333;
            padding:30px;
        }

        h1{
            margin-bottom:8px;
        }

        .subtitle{
            color:#666;
            margin-bottom:30px;
        }

        .cards{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
            gap:20px;
            margin-bottom:30px;
        }

        .card{
            background:#fff;
            border-radius:10px;
            padding:25px;
            box-shadow:0 2px 8px rgba(0,0,0,.08);
        }

        .card h3{
            font-size:14px;
            color:#666;
            margin-bottom:10px;
        }

        .card .value{
            font-size:32px;
            font-weight:bold;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:#fff;
            box-shadow:0 2px 8px rgba(0,0,0,.08);
        }

        th{
            background:#1f2937;
            color:#fff;
            padding:12px;
            text-align:left;
            white-space:nowrap;
        }

        td{
            padding:12px;
            border-bottom:1px solid #eee;
            white-space:nowrap;
        }

        tr:hover{
            background:#fafafa;
        }

        .success{
            color:#16a34a;
            font-weight:bold;
        }

        .failed{
            color:#dc2626;
            font-weight:bold;
        }

        .provider-free{
            color:#15803d;
            font-weight:bold;
        }

        .provider-paid{
            color:#ca8a04;
            font-weight:bold;
        }

        .status-200{
            color:#16a34a;
            font-weight:bold;
        }

        .status-429{
            color:#d97706;
            font-weight:bold;
        }

        .status-500,
        .status-502,
        .status-503,
        .status-504{
            color:#dc2626;
            font-weight:bold;
        }

        .table-wrapper{
            overflow-x:auto;
        }

    </style>

</head>

<body>

<h1>🤖 AI Usage Monitor</h1>

<div class="subtitle">

    CoreERP • Google Gemini OCR Monitoring

</div>

<div class="cards">

    <div class="card">

        <h3>Total Request</h3>

        <div class="value">

            {{ number_format($totalRequest ?? 0) }}

        </div>

    </div>

    <div class="card">

        <h3>Success</h3>

        <div class="value">

            {{ number_format($success ?? 0) }}

        </div>

    </div>

    <div class="card">

        <h3>Failed</h3>

        <div class="value">

            {{ number_format($failed ?? 0) }}

        </div>

    </div>

    <div class="card">

        <h3>Success Rate</h3>

        <div class="value">

            {{ $totalRequest ? number_format(($success / $totalRequest) * 100,2) : 0 }}%

        </div>

    </div>

    <div class="card">

        <h3>Total Token</h3>

        <div class="value">

            {{ number_format($totalToken ?? 0) }}

        </div>

    </div>

    <div class="card">

        <h3>Avg OCR</h3>

        <div class="value">

            {{ number_format($avgTime ?? 0) }} ms

        </div>

    </div>

</div>

<h2 style="margin-bottom:15px;">

    Radar Pantau Gemini

</h2>

<div class="table-wrapper">

<table>

<thead>

<tr>
<th>Waktu</th>

<th>Company</th>

<th>Provider</th>

<th>Model</th>

<th>Prompt</th>

<th>Output</th>

<th>Total</th>

<th>OCR</th>

<th>HTTP</th>

<th>Status</th>

</tr>

</thead>

<tbody>
    <tbody>

@forelse($logs ?? [] as $row)

<tr>

    <td>
        {{ $row->created_at }}
    </td>
        <td>
    {{ $row->company->name ?? '-' }}
</td>

    <td>

        @if(($row->provider ?? '') == 'Free')

            <span class="provider-free">
                🟢 {{ $row->provider }}
            </span>

        @elseif(($row->provider ?? '') == 'Paid')

            <span class="provider-paid">
                🟡 {{ $row->provider }}
            </span>

        @else

            {{ $row->provider ?? '-' }}

        @endif

    </td>

    <td>
        {{ $row->model }}
    </td>



    <td style="text-align:right;">
        {{ number_format($row->prompt_tokens ?? 0) }}
    </td>

    <td style="text-align:right;">
        {{ number_format($row->output_tokens ?? 0) }}
    </td>

    <td style="text-align:right;">
        {{ number_format($row->total_tokens ?? 0) }}
    </td>

    <td style="text-align:right;">
        {{ number_format($row->elapsed_ms ?? 0) }} ms
    </td>

    <td>

        @php
            $status = $row->http_status ?? '-';
        @endphp

        <span class="status-{{ $status }}">
            {{ $status }}
        </span>

    </td>

    <td>

        @if($row->success)

            <span class="success">
                ✅ SUCCESS
            </span>

        @else

            <span class="failed">
                ❌ FAILED
            </span>

        @endif

    </td>

</tr>

@empty

<tr>

    <td colspan="10" style="text-align:center;padding:30px;">

        Belum ada data penggunaan AI.

    </td>

</tr>

@endforelse

</tbody>

</table>

</div>
<div style="margin-top:25px;
            color:#666;
            font-size:13px;
            text-align:center;">

    CoreERP AI Monitoring • Google Gemini OCR •
    {{ now()->format('d-m-Y H:i:s') }}

</div>

</body>
</html>