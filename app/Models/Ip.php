<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    use HasFactory;
    protected $fillable = [
        'address',
        'country',
        'city'
    ];
    public function urls()
    {
        return $this->belongsToMany(Url::class);
    }
}
