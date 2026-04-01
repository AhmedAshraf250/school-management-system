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
        Schema::table('receipts', function (Blueprint $table): void {
            $table->softDeletes();
        });

        Schema::table('payments', function (Blueprint $table): void {
            $table->softDeletes();
        });

        Schema::table('processing_fees', function (Blueprint $table): void {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('processing_fees', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });

        Schema::table('payments', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });

        Schema::table('receipts', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });
    }
};
