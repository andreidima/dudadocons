<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProiectEmailTrimis extends Model
{
    protected $table = "proiecte_emailuri_trimise";
    protected $guarded = [];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
