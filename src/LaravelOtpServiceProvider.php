<?php

namespace SignatureTech\LaravelOtp;

use Illuminate\Support\ServiceProvider;

/**
 * Class LaravelOtpServiceProvider
 * Service provider for the Laravel OTP package.
 */
class LaravelOtpServiceProvider extends ServiceProvider
{
    /**
     * Register services by merging package configuration.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/otp.php', 'otp');
    }

    /**
     * Bootstrap services, including configuration publishing and migrations.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPublishing();
        $this->registerMigrations();
    }

    /**
     * Configure publishing of config and migrations for the package.
     *
     * @return void
     */
    private function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/otp.php' => config_path('otp.php'),
            ], 'otp-config');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'otp-migrations');
        }
    }

    /**
     * Register the package's migrations.
     *
     * @return void
     */
    private function registerMigrations(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }
}
