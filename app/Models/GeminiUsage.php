<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Company;

class GeminiUsage extends Model
{
    use HasFactory;

    protected $table = 'gemini_usages';

    protected $fillable = [
        'provider',
        'company_phone',
        'supplier',
        'invoice_number',
        'invoice_total',

        'model',

        'image_size_kb',

        'prompt_tokens',
        'output_tokens',
        'total_tokens',

        'elapsed_ms',

        'http_status',
        'success',

        'error_code',
        'error_message',
    ];

    protected $casts = [

        'invoice_total' => 'decimal:2',

        'image_size_kb' => 'integer',

        'prompt_tokens' => 'integer',
        'output_tokens' => 'integer',
        'total_tokens'  => 'integer',

        'elapsed_ms' => 'integer',

        'http_status' => 'integer',

        'success' => 'boolean',
    ];


public function company()
{
    return $this->belongsTo(
        Company::class,
        'company_phone',
        'phone'
    );
}
    
}
