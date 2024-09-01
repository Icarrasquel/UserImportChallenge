<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'subscriptions';
    protected $primaryKey   = 'id';
    protected $keyType      = 'uuid';
    public $incrementing    = false;

    protected $fillable = [
        'user_imports_id',
        'plan',
        'status',
        'payment_method',
        'term',
    ];

    public function userImport()
    {
        return $this->belongsTo(UserImport::class, 'user_imports_id', 'uid');
    }
}
