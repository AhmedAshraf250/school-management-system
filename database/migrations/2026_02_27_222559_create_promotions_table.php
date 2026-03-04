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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained('students')->restrictOnDelete();

            $table->foreignId('from_grade_id')->nullable()->constrained('grades')->nullOnDelete();
            $table->foreignId('from_classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
            $table->foreignId('from_section_id')->nullable()->constrained('sections')->nullOnDelete();

            $table->foreignId('to_grade_id')->nullable()->constrained('grades')->nullOnDelete();
            $table->foreignId('to_classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
            $table->foreignId('to_section_id')->nullable()->constrained('sections')->nullOnDelete();

            $table->string('academic_year_from', 20);
            $table->string('academic_year_to', 20);
            $table->timestamp('promoted_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->unique(['student_id', 'academic_year_to'], 'promotions_student_year_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
