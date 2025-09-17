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
        Schema::create('o_auths', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');
            $table->string('provider'); // e.g., 'google', 'facebook'
            $table->string('provider_id'); // uuid in DB
            $table->primary(['user_id', 'provider']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('o_auths');
    }
};
