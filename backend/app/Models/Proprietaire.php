<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proprietaire extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
    ];

    /**
     * Un propriétaire possède plusieurs biens.
     */
    public function biens(): HasMany
    {
        return $this->hasMany(Bien::class);
    }
}
