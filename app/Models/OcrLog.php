<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OcrLog extends Model
{
    protected $table = 'ocr_logs';

    protected $fillable = [

        'company_id',

        'image_file',

        'raw_json',

        'status',

        'source',

        'error_message',

    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}