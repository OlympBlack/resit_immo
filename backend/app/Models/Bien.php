<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bien extends Model
{
    protected $fillable = [
        'titre',
        'type',
        'adresse',
        'ville',
        'prix',
        'proprietaire_id',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
    ];

    /**
     * Un bien appartient à un propriétaire.
     */
    public function proprietaire(): BelongsTo
    {
        return $this->belongsTo(Proprietaire::class);
    }

    /**
     * Un bien peut avoir plusieurs contrats dans le temps.
     */
    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class);
    }
}
