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
        Schema::table(config('otp.table', 'otps'), function (Blueprint $table) {
            $table->string('event')->after('otp')->nullable();
            $table->bigInteger('model_id')->nullable()->change();
            $table->string('model_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(config('otp.table', 'otps'), function (Blueprint $table) {
            $table->dropColumn('event');
            $table->bigInteger('model_id')->change();
            $table->string('model_type')->change();
        });
    }
};
