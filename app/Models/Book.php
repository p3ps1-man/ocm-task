<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'description'
    ];

    public function authors(): HasMany
    {
        return $this->hasMany(Author::class);
    }
}
