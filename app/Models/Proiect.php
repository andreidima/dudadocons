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
        'data_limita_predare' => 'datetime',
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

    public function fisiere()
    {
        return $this->morphMany(Fisier::class, 'owner')->orderBy('nume_fisier');
    }

    public function clienti()
    {
        return $this->belongsToMany(Client::class, 'client_proiect')->withPivot('observatii')->orderBy('nume');
    }

    // public function membriDtacArhitectura()
    // {
    //     return $this->belongsToMany(Membru::class, 'membru_proiect')
    //         ->withPivot('id', 'tip', 'observatii')
    //         ->wherePivot('tip', 'dtac_arhitectura')
    //         ->orderBy('nume');
    // }

    public function membri()
    {
        return $this->belongsToMany(Membru::class, 'membru_proiect')
            ->withPivot('id', 'tip', 'observatii')
            ->orderBy('nume');
    }
}
