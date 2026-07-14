<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Gemini Usage Dashboard</title>

    <style>

        body{
            font-family:Arial,sans-serif;
            background:#f5f7fb;
            margin:30px;
            color:#333;
        }

        h1{
            margin-bottom:25px;
        }

        .cards{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
            gap:15px;
            margin-bottom:30px;
        }

        .card{
            background:#fff;
            border-radius:10px;
            padding:18px;
            box-shadow:0 2px 8px rgba(0,0,0,.08);
        }

        .card h3{
            margin:0;
            font-size:14px;
            color:#666;
        }

        .card .value{
            margin-top:8px;
            font-size:28px;
            font-weight:bold;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:#fff;
            margin-bottom:30px;
            box-shadow:0 2px 8px rgba(0,0,0,.08);
        }

        th{
            background:#2d3748;
            color:white;
            padding:10px;
            text-align:left;
        }

        td{
            padding:10px;
            border-bottom:1px solid #eee;
        }

        tr:hover{
            background:#fafafa;
        }

        h2{
            margin-top:40px;
            margin-bottom:10px;
        }

        .success{
            color:green;
            font-weight:bold;
        }

        .failed{
            color:red;
            font-weight:bold;
        }

    </style>

</head>
<body>

<h1>🚀 Gemini Usage Dashboard</h1>

<div class="cards">

    <div class="card">
        <h3>Total Request</h3>
        <div class="value">{{ number_format($summary['total_request']) }}</div>
    </div>

    <div class="card">
        <h3>Request Hari Ini</h3>
        <div class="value">{{ number_format($summary['today_request']) }}</div>
    </div>

    <div class="card">
        <h3>Berhasil</h3>
        <div class="value">{{ number_format($summary['success_request']) }}</div>
    </div>

    <div class="card">
        <h3>Gagal</h3>
        <div class="value">{{ number_format($summary['failed_request']) }}</div>
    </div>

    <div class="card">
        <h3>Prompt Tokens</h3>
        <div class="value">{{ number_format($summary['prompt_tokens']) }}</div>
    </div>

    <div class="card">
        <h3>Output Tokens</h3>
        <div class="value">{{ number_format($summary['output_tokens']) }}</div>
    </div>

    <div class="card">
        <h3>Total Tokens</h3>
        <div class="value">{{ number_format($summary['total_tokens']) }}</div>
    </div>

    <div class="card">
        <h3>Rata-rata Response</h3>
        <div class="value">{{ number_format($summary['avg_elapsed_ms']) }} ms</div>
    </div>

</div>

<h2>📅 Penggunaan per Hari</h2>

<table>

<thead>
<tr>
    <th>Tanggal</th>
    <th>Request</th>
    <th>Total Token</th>
</tr>
</thead>

<tbody>

@foreach($dailyUsage as $row)

<tr>

    <td>{{ $row->tanggal }}</td>

    <td>{{ number_format($row->total_request) }}</td>

    <td>{{ number_format($row->total_tokens) }}</td>

</tr>

@endforeach

</tbody>

</table>

<h2>🏢 Pengguna Terbanyak</h2>

<table>

<thead>

<tr>

    <th>No WA</th>

    <th>Request</th>

    <th>Total Token</th>

</tr>

</thead>

<tbody>

@foreach($topCompanies as $row)

<tr>

    <td>{{ $row->company_phone }}</td>

    <td>{{ number_format($row->total_request) }}</td>

    <td>{{ number_format($row->total_tokens) }}</td>

</tr>

@endforeach

</tbody>

</table>

<h2>🧾 Supplier Terbanyak</h2>

<table>

<thead>

<tr>

    <th>Supplier</th>

    <th>Request</th>

    <th>Total Token</th>

</tr>

</thead>

<tbody>

@foreach($topSuppliers as $row)

<tr>

    <td>{{ $row->supplier }}</td>

    <td>{{ number_format($row->total_request) }}</td>

    <td>{{ number_format($row->total_tokens) }}</td>

</tr>

@endforeach

</tbody>

</table>

<h2>📄 Log Terbaru</h2>

<table>

<thead>

<tr>

    <th>Waktu</th>

    <th>Model</th>

    <th>Supplier</th>

    <th>WA</th>

    <th>Prompt</th>

    <th>Output</th>

    <th>Total</th>

    <th>Status</th>

    <th>Waktu</th>

</tr>

</thead>

<tbody>

@foreach($recentLogs as $log)

<tr>

    <td>{{ $log->created_at }}</td>

    <td>{{ $log->model }}</td>

    <td>{{ $log->supplier }}</td>

    <td>{{ $log->company_phone }}</td>

    <td>{{ number_format($log->prompt_tokens) }}</td>

    <td>{{ number_format($log->output_tokens) }}</td>

    <td>{{ number_format($log->total_tokens) }}</td>

    <td>

        @if($log->success)

            <span class="success">SUCCESS</span>

        @else

            <span class="failed">FAILED</span>

        @endif

    </td>

    <td>{{ $log->elapsed_ms }} ms</td>

</tr>

@endforeach

</tbody>

</table>

<div style="margin-top:20px;">

{{ $recentLogs->links() }}

</div>

</body>
</html>