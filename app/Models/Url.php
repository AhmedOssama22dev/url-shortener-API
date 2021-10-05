<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Url extends Model
{
    use HasFactory;
    protected $fillable = [
        'original',
        'shortened',
        'clicks',
        'user_id'
    ];
    public function ips()
    {
        return $this->belongsToMany(Ip::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
