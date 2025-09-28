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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->text('bio')->nullable()->after('email_verified_at');
            $table->string('profile_image')->nullable()->after('bio');
            $table->enum('role', ['user', 'admin', 'master_admin'])
                  ->default('user')
                  ->after('profile_image');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'bio', 'profile_image', 'role']);
        });
    }
};