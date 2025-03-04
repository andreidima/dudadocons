<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proiect extends Model
{
    use HasFactory;

    protected $table = 'proiecte';
    protected $guarded = [];

    protected $casts = [
        'data_contract' => 'datetime',
        'data_proces_verbal_predare_primire' => 'datetime',
    ];

    public function path($action = 'show')
    {
        $tipSlug = $this->proiectTip->slug;
        return match ($action) {
            'edit' => route('proiecte.edit', ['proiectTip' => $tipSlug, 'proiect' => $this->id]),
            'destroy' => route('proiecte.destroy', ['proiectTip' => $tipSlug, 'proiect' => $this->id]),
            default => route('proiecte.show', ['proiectTip' => $tipSlug, 'proiect' => $this->id]),
        };
    }

    public function proiectTip()
    {
        return $this->belongsTo(ProiectTip::class, 'proiecte_tipuri_id');
    }

    public function membri()
    {
        return $this->belongsToMany(Membru::class, 'membru_proiect');
    }

    public function subcontractanti()
    {
        return $this->belongsToMany(Subcontractant::class, 'proiect_subcontractant');
    }

    public function fisiere()
    {
        return $this->morphMany(Fisier::class, 'owner');
    }

    /**
     * Get all of the emailuriTrimise for the Proiect
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emailuriTrimise(): HasMany
    {
        return $this->hasMany(ProiectEmailTrimis::class, 'proiect_id');
    }

    public function emailuriTrimiseCountForRecipient($destinatarId, $destinatarType)
    {
        return $this->emailuriTrimise()
                    ->where('destinatar_id', $destinatarId)
                    ->where('destinatar_type', $destinatarType)
                    ->count();
    }
}
