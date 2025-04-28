<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    protected $fillable = [
        'name',
        'birth_year',
        'death_year'
    ];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }
}
