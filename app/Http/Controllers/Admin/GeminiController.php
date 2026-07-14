<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeminiUsage;

class GeminiController extends Controller
{
    public function index()
    {
        $logs = GeminiUsage::with('company')
            ->latest()
            ->paginate(50);

        $totalRequest = GeminiUsage::count();

        $success = GeminiUsage::where('success', true)->count();

        $failed = GeminiUsage::where('success', false)->count();

        $totalToken = GeminiUsage::sum('total_tokens');

        $avgTime = round(
            GeminiUsage::avg('elapsed_ms') ?? 0
        );

        return view('admin.gemini.index', [

            'logs'         => $logs,

            'totalRequest' => $totalRequest,

            'success'      => $success,

            'failed'       => $failed,

            'totalToken'   => $totalToken,

            'avgTime'      => $avgTime,

        ]);
    }
}