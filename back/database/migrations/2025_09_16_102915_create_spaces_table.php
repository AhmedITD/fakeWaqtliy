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
        Schema::create('spaces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('size')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('floor')->nullable();
            $table->integer('price_per_hour')->nullable(); // price in cents/smallest currency unit
            $table->string('thumbnail')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('organization_id')->nullable()->constrained('organizations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spaces');
    }
};
