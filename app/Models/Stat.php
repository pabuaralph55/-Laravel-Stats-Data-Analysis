<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Publisher;

class Stat extends Model
{
    use HasFactory;
    protected $table = 'stats';

    protected $fillable = [
        'day',
        'country_iso',
        'plaftform_id',
        'publisher_id',
        'impressions',
        'conversions',
    ];

    // public function publisher()
    // {
    //     return $this->belongsTo(Publisher::class);
    // }
}
