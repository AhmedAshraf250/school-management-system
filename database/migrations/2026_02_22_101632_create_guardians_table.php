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
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');

            // Fatherinformation
            $table->string('father_name');
            $table->string('father_national_id');
            $table->string('father_passport_id');
            $table->string('father_phone');
            $table->string('father_job');
            $table->foreignId('father_nationality_id')->nullable()->constrained('nationalities')->nullOnDelete();
            $table->foreignId('father_blood_type_id')->nullable()->constrained('blood_types')->nullOnDelete();
            $table->foreignId('father_religion_id')->nullable()->constrained('religions')->nullOnDelete();
            $table->string('father_address');

            // Mother information
            $table->string('mother_name');
            $table->string('mother_national_id');
            $table->string('mother_passport_id');
            $table->string('mother_phone');
            $table->string('mother_job');
            $table->foreignId('mother_nationality_id')->nullable()->constrained('nationalities')->nullOnDelete();
            $table->foreignId('mother_blood_type_id')->nullable()->constrained('blood_types')->nullOnDelete();
            $table->foreignId('mother_religion_id')->nullable()->constrained('religions')->nullOnDelete();
            $table->string('mother_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};
