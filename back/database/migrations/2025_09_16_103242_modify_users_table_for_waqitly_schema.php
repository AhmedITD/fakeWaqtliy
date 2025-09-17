<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rename 'name' to 'full_name' and make it nullable
            $table->renameColumn('name', 'full_name');
            $table->string('full_name')->nullable()->change();
            
            // Add new fields
            $table->string('username')->nullable()->after('full_name');
            $table->string('email')->nullable()->change();
            $table->string('image')->nullable()->after('email');
            $table->text('bio')->nullable()->after('image');
            $table->enum('role', ['super_admin', 'admin', 'staff', 'user'])->default('user')->after('bio');
            $table->string('gender')->nullable()->after('role');
            $table->date('birth_date')->nullable()->after('gender');
            
            // Remove default Laravel fields we don't need
            $table->dropColumn(['email_verified_at', 'password', 'remember_token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse the changes
            $table->renameColumn('full_name', 'name');
            $table->string('name')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            
            $table->dropColumn(['username', 'image', 'bio', 'role', 'gender', 'birth_date']);
            
            // Add back Laravel defaults
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
        });
    }
};
