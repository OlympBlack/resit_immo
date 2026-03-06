<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Locataire extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
    ];

    /**
     * Un locataire peut avoir plusieurs contrats dans le temps.
     */
    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class);
    }
}
