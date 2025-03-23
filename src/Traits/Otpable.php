<?php

namespace SignatureTech\LaravelOtp\Traits;

use SignatureTech\LaravelOtp\Models\Otp;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use SignatureTech\LaravelOtp\Exceptions\OtpExpiredException;
use SignatureTech\LaravelOtp\Exceptions\OtpInvalidException;

/**
 * Trait Otpable
 * Adds OTP functionality to a model.
 */
trait Otpable
{
    /**
     * OTP length property (optional).
     *
     * @var int|null
     */
    public ?int $length = null;

    /**
     * Define a one-to-many polymorphic relationship for OTPs.
     *
     * @return MorphMany
     */
    public function otps(): MorphMany
    {
        return $this->morphMany(Otp::class, 'model');
    }

    /**
     * Creates and associates an OTP with the model.
     *
     * @param Otp $otp The OTP model instance.
     * @return Otp The saved OTP instance.
     */
    public function createOtp(Otp $otp): Otp
    {
        return isset($otp->id) ? $otp : $this->otps()->save($otp);
    }

    /**
     * Verifies an OTP.
     *
     * @param Otp $otpModel The OTP model instance.
     * @param string $otp The OTP value to verify.
     * @throws OtpExpiredException If the OTP has expired.
     * @throws OtpInvalidException If the OTP is invalid.
     * @return bool True if OTP is valid, false otherwise.
     */
    public function verifyOtp(Otp $otpModel, string $otp): bool
    {
        return $otpModel->verifyOtp($otp);
    }
}
