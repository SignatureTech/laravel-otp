<?php

namespace SignatureTech\LaravelOtp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'model_id',
        'model_type',
        'receiver',
        'otp',
        'expired_at',
        'used_at'
    ];

    /**
     * @return [type]
     */
    public function getOtp()
    {
        return $this->otp;
    }
}
