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
        Schema::create('local_auths', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained('users');
            $table->string('phone_number')->nullable();
            $table->string('password_hash');
            $table->boolean('is_phone_verified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_auths');
    }
};
