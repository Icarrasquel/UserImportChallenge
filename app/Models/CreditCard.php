<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'credit_cards';
    protected $primaryKey   = 'id';
    protected $keyType      = 'uuid';
    public $incrementing    = false;

    protected $fillable = [
        'user_imports_id',
        'cc_number',
    ];

    public function userImport()
    {
        return $this->belongsTo(UserImport::class, 'user_imports_id', 'uid');
    }
}
