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
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->string('particulars');
            $table->enum('status', ['Ordered', 'In-Transit', 'Delivered'])->default('Ordered');
            $table->dateTime('ordered_on');
            $table->dateTime('delivered_on')->nullable();
            $table->unsignedBigInteger('action_taken_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('action_taken_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
};
