<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    public function getPriceInDollarsAttribute()
    {
        return number_format($this->price / 100, 2, '.', ' ');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
