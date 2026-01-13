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
        Schema::create('program_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('active');
            $table->foreignId('sub_program_id')->nullable()->constrained('sub_programs','id');
        });

        Schema::create('organizational_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('active');
        });

        Schema::create('program_indicator_disaggregations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_indicator_id')->constrained('program_indicators','id');
            $table->foreignId('disaggregation_id')->constrained('disaggregations','id');
        });

        Schema::create('barangay_org_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barangay_id')->constrained('barangays','id');
            $table->foreignId('organizational_indicator_id')->constrained('organizational_indicators','id');
            $table->integer('value');
            $table->text('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicators');
        Schema::dropIfExists('organizational_indicators');
    }
};
