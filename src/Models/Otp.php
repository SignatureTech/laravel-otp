<?php

namespace SignatureTech\LaravelOtp\Models;

use Illuminate\Database\Eloquent\Model;
use SignatureTech\LaravelOtp\Exceptions\OtpExpiredException;
use SignatureTech\LaravelOtp\Exceptions\OtpInvalidException;
use Carbon\Carbon;

/**
 * Class Otp
 * Represents an OTP model with verification capabilities.
 */
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
        'event',
    ];

    /**
     * Get the OTP value.
     *
     * @return string OTP string.
     */
    public function getOtp(): string
    {
        return $this->otp;
    }

    /**
     * Verify the provided OTP.
     *
     * @param string $otp The OTP to verify.
     * @throws OtpExpiredException If the OTP is expired.
     * @throws OtpInvalidException If the OTP is invalid.
     * @return bool True if the OTP is valid.
     */
    public function verifyOtp(string $otp): bool
    {
        if (Carbon::now()->greaterThan($this->expired_at)) {
            throw new OtpExpiredException(__('OTP Expired'));
        }

        if ($this->otp !== $otp) {
            throw new OtpInvalidException(__('Invalid OTP'));
        }

        $this->update(['used_at' => Carbon::now()]);

        return true;
    }
}
