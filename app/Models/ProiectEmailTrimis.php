<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProiectEmailTrimis extends Model
{
    protected $table = "proiecte_emailuri_trimise";
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
