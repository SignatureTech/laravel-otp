<?php

namespace SignatureTech\LaravelOtp;

use Illuminate\Support\Str;
use SignatureTech\LaravelOtp\Models\Otp as OtpModel;
use SignatureTech\LaravelOtp\Exceptions\OtpInvalidException;

/**
 * Class Otp
 * Handles OTP generation, validation, and management.
 */
class Otp
{
    /**
     * OTP length.
     */
    protected int $length;

    /**
     * OTP expiry time in minutes.
     */
    protected int $expiry;

    /**
     * OTP format (alpha, alphanumeric, numeric).
     */
    protected string $format;

    /**
     * Receiver identifier (e.g., email or phone number).
     */
    protected string $receiver;

    /**
     * Manually set OTP value (optional).
     */
    protected mixed $otp = null;

    /**
     * Available OTP formats.
     */
    protected array $availableFormats = ["alpha", "alphanumeric", "numeric"];

    /**
     * Otp constructor.
     *
     * @param mixed $receiver The receiver identifier.
     */
    public function __construct(mixed $receiver)
    {
        $this->receiver = $receiver;
        $this->length = config('otp.length', 6);
        $this->expiry = config('otp.expiry', 5);
        $this->format = config('otp.format', 'numeric');
    }

    /**
     * Creates an instance for the given receiver.
     *
     * @param mixed $receiver The receiver identifier.
     * @return self New Otp instance.
     */
    public static function for(mixed $receiver): self
    {
        return new self($receiver);
    }

    /**
     * Sets the OTP expiry time.
     *
     * @param int $expiry Expiry time in minutes.
     * @return self Current instance.
     */
    public function setExpiry(int $expiry): self
    {
        if ($expiry > 0) {
            $this->expiry = $expiry;
        }
        return $this;
    }

    /**
     * Sets the OTP length.
     *
     * @param int $length Length of the OTP.
     * @return self Current instance.
     */
    public function setLength(int $length): self
    {
        if ($length > 0) {
            $this->length = $length;
        }
        return $this;
    }

    /**
     * Sets a default OTP value.
     *
     * @param string|null $otp Custom OTP value.
     * @return self Current instance.
     */
    public function setDefault(?string $otp = null): self
    {
        $this->otp = $otp ?? config('otp.default_otp');
        return $this;
    }

    /**
     * Sets the OTP format.
     *
     * @param string $format OTP format (alpha, alphanumeric, numeric).
     * @return self Current instance.
     */
    public function setFormat(string $format): self
    {
        if (in_array($format, $this->availableFormats, true)) {
            $this->format = $format;
        }
        return $this;
    }

    /**
     * Checks if a valid OTP exists for the receiver.
     *
     * @return OtpModel|null Existing OTP model if found, otherwise null.
     */
    public function checkOtp(): ?OtpModel
    {
        return OtpModel::query()
            ->where('receiver', $this->receiver)
            ->where('expired_at', '>=', now())
            ->whereNull('used_at')
            ->first();
    }

    /**
     * Generates and saves a new OTP for the receiver.
     * If an active OTP exists, it returns the existing OTP.
     *
     * @param string|null $event Event identifier (optional).
     * @return OtpModel OTP model instance.
     */
    public function create(?string $event = null): OtpModel
    {
        if ($otp = $this->checkOtp()) {
            return $otp;
        }

        return OtpModel::create([
            'receiver' => $this->receiver,
            'event' => $event,
            'otp' => $this->generateOtp(),
            'expired_at' => now()->addMinutes($this->expiry),
        ]);
    }

    /**
     * Generates and saves a new OTP for the receiver.
     * If an active OTP exists, it returns the existing OTP.
     *
     * @param string|null $event Event identifier (optional).
     * @return OtpModel OTP model instance.
     */
    public function generate(?string $event = null): OtpModel
    {
        if ($otp = $this->checkOtp()) {
            return $otp;
        }

        return new OtpModel([
            'receiver' => $this->receiver,
            'event' => $event,
            'otp' => $this->generateOtp(),
            'expired_at' => now()->addMinutes($this->expiry),
        ]);
    }

    /**
     * Generates an OTP based on the selected format.
     *
     * @return mixed Generated OTP.
     */
    protected function generateOtp(): mixed
    {
        if ($this->otp) {
            return $this->otp;
        }

        if (app()->environment() !== 'production') {
            return config('otp.default_otp', '123456');
        }

        return match ($this->format) {
            'alpha' => substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $this->length),
            'alphanumeric' => Str::upper(Str::random($this->length)),
            default => random_int(pow(10, $this->length - 1), pow(10, $this->length) - 1),
        };
    }

    /**
     * Retrieves the latest unused OTP for the receiver.
     *
     * @throws OtpInvalidException If no valid OTP is found.
     * @return OtpModel OTP model instance.
     */
    public function getOtp(): OtpModel
    {
        $otp = OtpModel::query()
            ->where('receiver', $this->receiver)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otp) {
            throw new OtpInvalidException(__('Invalid OTP'));
        }

        return $otp;
    }
}
