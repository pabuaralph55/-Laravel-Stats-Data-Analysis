<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Stat;

class Publisher extends Model
{
    use HasFactory;
    protected $table = 'publisher';

    protected $fillable = [
        'name',
    ];

    public function stats()
    {
        return $this->hasMany(Stat::class);
    }
}
