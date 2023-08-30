<?php

namespace SignatureTech\LaravelOtp\Traits;

use SignatureTech\LaravelOtp\Models\Otp;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use SignatureTech\LaravelOtp\Exceptions\OtpExpiredException;
use SignatureTech\LaravelOtp\Exceptions\OtpInvalidException;

trait Otpable
{
    public $length;

    /**
     * @return MorphMany
     */
    public function otps(): MorphMany
    {
        return $this->morphMany(Otp::class, 'model');
    }

    /**
     * @param mixed $otp
     *
     * @return Otp
     */
    public function createOtp($otp)
    {
        return isset($otp->id) ? $otp : $this->otps()->save($otp);
    }

    /**
     * @param mixed $otpModel
     * @param mixed $otp
     *
     * @return boolean
     */
    public function verifyOtp($otpModel, $otp)
    {
        if (now()->gt($this->expired_at)) {
            throw new OtpExpiredException(__('Otp Expired'));
        }

        if ($otpModel->otp != $otp) {
            throw new OtpInvalidException(__('Invalid Otp'));
        }

        $otpModel->used_at = now();
        $otpModel->save();

        return true;
    }
}
