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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submitted_by')->nullable()->constrained('users','id');
            $table->date('date')->nullable();
            $table->foreignId('barangay_id')->constrained('barangays','id');
            $table->integer('total_clients')->nullable();
            $table->integer('total_returning_clients')->nullable();
            $table->timestamps();
        });

        Schema::create('report_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->nullable()->constrained('reports','id');
            $table->foreignId('sub_program_id')->nullable()->constrained('sub_programs','id');
            $table->foreignId('program_indicator_id')->nullable()->constrained('program_indicators','id');
            // $table->foreignId('organizational_indicator_id')->nullable()->constrained('organizational_indicators','id');
            // $table->foreignId('disaggregation_id')->nullable()->constrained('disaggregations','id');
            // $table->string('indicator_type')->nullable();//program,organizational
            $table->integer('total_value')->nullable();
            $table->timestamps();
        });

        Schema::create('report_value_disaggregations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_value_id')->constrained('report_values','id');
            $table->foreignId('disaggregation_id')->constrained('disaggregations','id');
            $table->integer('value');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
