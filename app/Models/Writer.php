<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Writer extends Model
{
    protected $fillable = ['short_bio', 'editorial'];
    public $timestamps = false;

    use HasFactory;

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }
}
