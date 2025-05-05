<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clienti';
    protected $guarded = [];

    protected $casts = [
        'data_inceput_contract' => 'datetime',
        'data_sfarsit_contract' => 'datetime',
    ];

    public function path($action = 'show')
    {
        return match ($action) {
            'edit' => route('clienti.edit', $this->id),
            'destroy' => route('clienti.destroy', $this->id),
            default => route('clienti.show', $this->id),
        };
    }

    public function proiecte()
    {
        return $this->belongsToMany(Proiect::class, 'proiect_subcontractant');
    }
}
