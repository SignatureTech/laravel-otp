<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel">
<h1>LaravelOtp : generate OTP and Validate OTP</h1>

---

## Table of contents

- [Introduction](#introduction)
- [Todo](#todo)
- [License](#license)

## Introduction

`LaravelOtp` is a [Laravel](https://laravel.com/) package, designed to generate OTP and Validate OTP using simple steps. This packages will show all OTP history.

## Todo

- [x] Generate OTP
- [x] Verify OTP
- [x] OTP Lists
- [ ] Test Cases

## Features

- Generate OTP
- Verify OTP
- Get the User OTP List
- Generate custom digit, type OTP

## Installation & Configuration

You can install this package via composer using:

```
composer require signaturetech/laravel-otp
```

Now add the `use SignatureTech\LaravelOtp\Traits\Otpable` trait to your model.

```
use SignatureTech\LaravelOtp\Traits\Otpable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Otpable;
}
```

### Generate OTP

Please use below code to generate otp:

1. Get User Details

```
use App\Models\User;

$user = User::first();
```

2. Create Otp Insatance

```
use SignatureTech\LaravelOtp\Otp;


$otp = Otp::for($user->email)->generate();
```

**Note:** You can use email/mobile/phone number to generate otp Just pass the detail using `for` method.

**Note:** You can use more method to setting otp all methods described in `methods` section.

3. Attach Otp with user

```
$userOtp = $user->createOtp($otp);

$otp = $user->otp;
```

### Verify OTP

You can verifu otp by useing below code:

1. Get the use details

```
use App\Models\User;

$user = User::first();
```

2. Get Otp Insatance

```
use SignatureTech\LaravelOtp\Otp;


$otp = Otp::for($user->email)->getOtp();
```

3. Verify Otp

```
try {
    $user->verifyOtp($otp, $request->get('otp'));
} catch (OtpInvalidException $e) {
    return $e->getMessage;
} catch (OtpExpiredException $e) {
    return $e->getMessage;
}
```

## License

- Written and copyrighted &copy;2022 by Prem Chand Saini ([prem@signaturetech.in](mailto:prem@signaturetech.in))
- ResponseBuilder is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
