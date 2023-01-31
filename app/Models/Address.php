<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'state',
        'county',
        'city',
        'zip',
        'street',
        'house_number',
        'latitude',
        'longitude',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toSearchableArray()
    {
        return [
            'state' => $this->state,
            'county' => $this->county,
            'city' => $this->county,
            'zip' => $this->county,
            'street' => $this->county,
            'house_number' => $this->county,
        ];
    }
}
