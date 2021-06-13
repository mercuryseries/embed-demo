<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['tickets'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'starts_at' => 'datetime'
    ];

    public function getStartsAtAttribute($value)
    {
        return $this->castAttribute('starts_at', $value)->format('F d, Y \a\t h:i A');
    }

    public function getPriceAttribute()
    {
        $tickets = $this->tickets->sortBy('price');

        $minPrice = $tickets->first()->price_in_dollars;

        if ($this->tickets->count() == 1) {
            return '$'. $minPrice;
        }

        $maxPrice = $tickets->last()->price_in_dollars;

        return sprintf("From $%s to $%s.", $minPrice, $maxPrice);
    }

    protected static function booted()
    {
        static::creating(function ($event) {
            $event->slug = Str::slug($event->name);
        });
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
