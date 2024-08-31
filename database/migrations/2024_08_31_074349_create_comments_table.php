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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drawing_details_id');
            $table->datetime('commented_at');
            $table->unsignedBigInteger('commented_by');
            $table->text('comment_body');
            $table->datetime('resubmitted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('drawing_details_id')->references('id')->on('drawing_details')->onDelete('cascade');
            $table->foreign('commented_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
