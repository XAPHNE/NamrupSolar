<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('must_change_password');
            $table->boolean('is_admin');
            $table->boolean('is_creator');
            $table->boolean('is_editor');
            $table->boolean('is_approver');
            $table->boolean('is_viewer');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@apgcl.org',
            'password' => bcrypt('admin@123'),
            'must_change_password' => false,
            'is_admin' => true,
            'is_creator' => false,
            'is_editor' => false,
            'is_approver' => false,
            'is_viewer' => false,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
