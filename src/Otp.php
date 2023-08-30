<?php

namespace SignatureTech\LaravelOtp;

use Illuminate\Support\Str;
use SignatureTech\LaravelOtp\Models\Otp as OtpModel;
use SignatureTech\LaravelOtp\Exceptions\OtpInvalidException;

class Otp
{
    /**
     * @var int
     */
    protected int $length;

    /**
     * @var int
     */
    protected int $expiry;

    /**
     * @var string
     */
    protected string $format;

    /**
     * @var string
     */
    protected string $receiver;

    /**
     * @var mixed
     */
    protected mixed $otp;

    /**
     * @var array
     */
    protected array $availableFormats = ["alpha", "alphanumeric", "numeric"];

    /**
     * @param mixed $receiver
     */
    public function __construct(mixed $receiver)
    {
        $this->receiver = $receiver;
        $this->length = config('otp.length');
        $this->expiry = config('otp.expiry');
        $this->format = config('otp.format');
    }

    /**
     * @param mixed $receiver
     *
     * @return self
     */
    public static function for($receiver): self
    {
        return new static($receiver);
    }

    /**
     * @param mixed $expiry
     *
     * @return self
     */
    public function setExpiry($expiry): self
    {
        $expiry = (int) $expiry;

        if ($expiry > 0) {
            $this->expiry = $expiry;
        }

        return $this;
    }

    /**
     * @param mixed $length
     *
     * @return self
     */
    public function setLength($length): self
    {
        $length = (int) $length;

        if ($length > 0) {
            $this->length = $length;
        }

        return $this;
    }

    /**
     * @param mixed $format
     *
     * @return self
     */
    public function setFormat($format): self
    {
        if (in_array($format, $this->availableFormats)) {
            $this->format = $format;
        }

        return $this;
    }

    /**
     * @return OtpModel
     */
    public function generate()
    {
        // check exiting otp
        $otp = OtpModel::query()
            ->where('receiver', $this->receiver)
            ->where('expired_at', '>=', now())
            ->whereNull('used_at')
            ->first();

        if ($otp) {
            return $otp;
        }

        $this->generateOtp();

        $model = new OtpModel([
            'receiver' => $this->receiver,
            'otp' => app()->environment() == 'local' ? config('otp.default_otp') : $this->generateOtp(),
            'expired_at' => now()->addMinutes($this->expiry)
        ]);

        return $model;
    }

    /**
     * @return mixed
     */
    protected function generateOtp()
    {
        switch ($this->format) {
            case "alpha":
                $alpha = "QWERTYUIOPASDFGHJKLZXCVBNMABCDEFGHIJKLMNOPQRSTUVWXYZMNBVCXZASDFGHJKLPOIUYTREWQ";
                $this->otp = substr(str_shuffle($alpha), 0, $this->length);
                break;
            case "alphanumeric":
                $this->otp = strtoupper(Str::random($this->length));
                break;
            default:
                $this->otp = mt_rand(pow(10, ($this->length - 1)), pow(10, $this->length) - 1);
        }

        return $this->otp;
    }

    /**
     * @return OtpModel
     */
    public function getOtp()
    {
        $otp = OtpModel::query()
            ->where('receiver', $this->receiver)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (empty($otp)) {
            throw new OtpInvalidException(__('Invalid Otp'));
        }

        return $otp;
    }
}
