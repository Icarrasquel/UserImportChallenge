<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id'; 
    public $incrementing = true; 
    protected $keyType = 'int';

    protected $fillable = [
        'user_imports_id',
        'city',
        'street_name',
        'street_address',
        'zip_code',
        'state',
        'country'
    ];

    public function userImport()
    {
        return $this->belongsTo(UserImport::class, 'user_imports_id', 'uid');
    }

    public function coordinate()
    {
        return $this->hasOne(Coordinate::class, 'address_id', 'id');
    }
}
