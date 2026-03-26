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
        Schema::table('online_classes', function (Blueprint $table) {
            $table->string('created_by')->nullable()->after('user_id');
            $table->index('created_by');
        });

        $legacyRows = DB::table('online_classes')
            ->leftJoin('users', 'users.id', '=', 'online_classes.user_id')
            ->whereNull('online_classes.created_by')
            ->select('online_classes.id', 'users.email')
            ->get();

        foreach ($legacyRows as $legacyRow) {
            DB::table('online_classes')
                ->where('id', $legacyRow->id)
                ->update([
                    'created_by' => $legacyRow->email,
                ]);
        }

        Schema::table('online_classes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('online_classes', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete()
                ->after('section_id');
            $table->dropIndex('online_classes_created_by_index');
            $table->dropColumn('created_by');
        });
    }
};
