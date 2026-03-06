<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contrat extends Model
{
    protected $fillable = [
        'bien_id',
        'locataire_id',
        'date_debut',
        'date_fin',
        'montant_mensuel',
        'statut',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'montant_mensuel' => 'decimal:2',
    ];

    /**
     * Un contrat concerne un seul bien.
     */
    public function bien(): BelongsTo
    {
        return $this->belongsTo(Bien::class);
    }

    /**
     * Un contrat concerne un seul locataire.
     */
    public function locataire(): BelongsTo
    {
        return $this->belongsTo(Locataire::class);
    }
}
