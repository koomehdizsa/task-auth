<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Author extends Model
{
    protected $fillable = [
        'title',
        'shaba',
        'description',
        'profit',
        'slug'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at'
    ];


    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'accountable');
    }
}
