<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyCode extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Get the users for the company code.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
