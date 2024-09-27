<?php

namespace SignatureTech\LaravelOtp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SignatureTech\LaravelOtp\Exceptions\OtpExpiredException;
use SignatureTech\LaravelOtp\Exceptions\OtpInvalidException;

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
        'used_at',
        'event'
    ];

    /**
     * @return [type]
     */
    public function getOtp()
    {
        return $this->otp;
    }

    public function verifyOtp($otp)
    {
        if (now()->gt($this->expired_at)) {
            throw new OtpExpiredException(__('Otp Expired'));
        }

        if ($this->otp != $otp) {
            throw new OtpInvalidException(__('Invalid Otp'));
        }

        $this->used_at = now();
        $this->save();

        return true;
    }
}
