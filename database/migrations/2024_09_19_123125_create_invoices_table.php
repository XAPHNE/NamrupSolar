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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no');
            $table->string('description');
            $table->double('amount');
            $table->dateTime('raised_at');
            $table->string('file_path');
            $table->dateTime('paid_at')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
