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
        Schema::create('drawing_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drawing_id');
            $table->string('drawing_details_name');
            $table->string('drawing_details_no');
            $table->boolean('isScopeDrawing')->default(true);
            $table->datetime('submitted_at')->nullable();
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->datetime('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('filepath')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('drawing_id')->references('id')->on('drawings')->onDelete('cascade');
            $table->foreign('submitted_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drawing_details');
    }
};
