<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $fillable = [
        'name',
        'birth_year',
        'death_year'
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
