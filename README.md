<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel">
<h1>LaravelOtp : generate OTP and Validate OTP</h1>

---

![GitHub](https://img.shields.io/github/license/signaturetech/laravel-otp)
![Packagist Downloads](https://img.shields.io/packagist/dt/signaturetech/laravel-otp)

## Table of contents

- [Introduction](#introduction)
- [Todo](#todo)
- [License](#license)

## Introduction

`LaravelOtp` is a [Laravel](https://laravel.com/) package, designed to generate OTP and Validate OTP using simple steps. This packages will show all OTP history.

## Todo

- [x] Generate OTP
- [x] Verify OTP
- [x] Methods
- [ ] OTP Lists
- [ ] Test Cases

## Features

- Generate OTP
- Verify OTP
- Get the User OTP List
- Generate custom lenth, expiry and formate OTP

## Installation & Configuration

You can install this package via composer using:

```
composer require signaturetech/laravel-otp
```

Next run the command below to setup api-response.config file, you can set your configuration.

```
php artisan vendor:publish --tag=otp-config
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

2. Create Otp Instance

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

You can verify otp by using below code:

1. Get the use details

```
use App\Models\User;

$user = User::first();
```

2. Get Otp Instance

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

### Create OTP without model

You can also create otp without model using the following:

1. Create OTP

```
use SignatureTech\LaravelOtp\Otp;


$otp = Otp::for($user->email)->create();
```

**Note:** You can use email/mobile/phone number to generate otp Just pass the detail using `for` method.

**Note:** You can use more method to setting otp all methods described in `methods` section.

2. Verify Otp

```
try {
    $otp->verifyOtp($request->get('otp'));
} catch (OtpInvalidException $e) {
    return $e->getMessage;
} catch (OtpExpiredException $e) {
    return $e->getMessage;
}
```

## Methods

1. Set length of otp

```
use SignatureTech\LaravelOtp\Otp;

// Set Length of OTP
$otp = Otp::for($user->email)->setLength(4)->generate();
```

**Note:** Default length is 6 digit and you can change the default digit to add the `OTP_LENGTH=4` in `.env` or `config/otp.php` file

2. Set Format (Available Format: alpha | alphanumeric | numeric)

```
use SignatureTech\LaravelOtp\Otp;

// Set Format (Available Format: alpha | alphanumeric | numeric)
$otp = Otp::for($user->email)->setFormat('numeric')->generate();
```

**Note:** Default format is numeric and you can change the default format to add the `OTP_FORMAT=4` in `.env` or `config/otp.php` file

2. Set Expiry (In minutes)

```
use SignatureTech\LaravelOtp\Otp;

// Set Format (Available Format: alpha | alphanumeric | numeric)
$otp = Otp::for($user->email)->setExpiry(20)->generate();
```

**Note:** Default expiry is 10 minutes and you can change this to add the `OTP_EXPIRY=20` in `.env` or `config/otp.php` file

## License

- Written and copyrighted &copy;2022 by Prem Chand Saini ([prem@signaturetech.in](mailto:prem@signaturetech.in))
- ResponseBuilder is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
