<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coordinate extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'coordinates';
    protected $primaryKey   = 'id';
    protected $keyType      = 'uuid';
    public $incrementing    = false;

    protected $fillable = [
        'address_id',
        'lat',
        'lng'
    ];

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }
}
