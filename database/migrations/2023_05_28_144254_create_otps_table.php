<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(config('otp.table', 'otps'), function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->string('receiver');
            $table->string('otp');
            $table->timestamp('expired_at')->useCurrent();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('otp.table', 'otps'));
    }
};
