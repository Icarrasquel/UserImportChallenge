<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserImport extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'user_imports';
    protected $primaryKey   = 'uid';
    protected $keyType      = 'uuid';
    public $incrementing    = false;

    protected $fillable = [
        'uid',
        'password',
        'first_name',
        'last_name',
        'username',
        'email',
        'avatar',
        'gender',
        'phone_number',
        'social_insurance_number',
        'date_of_birth'
    ];

    public function employment()
    {
        return $this->hasOne(Employment::class , 'user_imports_id', 'uid');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'user_imports_id', 'uid');
    }

    public function creditCard()
    {
        return $this->hasOne(CreditCard::class, 'user_imports_id', 'uid');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'user_imports_id', 'uid');
    }
}
