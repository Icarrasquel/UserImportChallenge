<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'employments';
    protected $primaryKey   = 'id';
    protected $keyType      = 'uuid';
    public $incrementing    = false;

    protected $fillable = [
        'user_imports_id',
        'title',
        'key_skill'
    ];

    public function userImport()
    {
        return $this->belongsTo(UserImport::class, 'user_imports_id', 'uid');
    }
}
