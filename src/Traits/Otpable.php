<?php

namespace SignatureTech\LaravelOtp\Traits;

use SignatureTech\LaravelOtp\Models\Otp;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Otpable
{
    public $length;

    public function otps(): MorphMany
    {
        return $this->morphMany(Otp::class, 'model');
    }

    public function createOtp($otp)
    {
        return $this->otps()->save($otp);
    }
}
