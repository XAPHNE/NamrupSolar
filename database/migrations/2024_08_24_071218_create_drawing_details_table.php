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
            $table->boolean('isSubmitted')->default(false);
            $table->boolean('isApproved')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('drawing_id')->references('id')->on('drawings')->onDelete('cascade');
        });

        DB::table('drawing_details')->insert([
            [
                'drawing_id' => 1,
                'drawing_details_name' => 'Contour Survey Report',
                'drawing_details_no' => 'APGCL-AR-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'drawing_id' => 1,
                'drawing_details_name' => 'Geo-Technical Investigation Report',
                'drawing_details_no' => 'APGCL-AR-02',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'drawing_id' => 1,
                'drawing_details_name' => 'PVsyst Report',
                'drawing_details_no' => 'APGCL-AR-03',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drawing_details');
    }
};
