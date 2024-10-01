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
            $table->unsignedBigInteger('drawing_file_id'); // Ensure this column is named correctly
            $table->text('comment_body');
            $table->timestamp('commented_at')->nullable();
            $table->unsignedBigInteger('commented_by');
            $table->timestamp('resubmitted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key constraints
            $table->foreign('drawing_file_id')->references('id')->on('drawing_files')->onDelete('cascade');
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
