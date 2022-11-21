<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'title',
        'overview',
        'price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
