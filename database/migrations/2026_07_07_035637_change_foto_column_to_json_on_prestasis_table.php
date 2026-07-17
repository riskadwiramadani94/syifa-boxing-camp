<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First convert existing strings to valid JSON arrays
        DB::table('galeris')->whereNotNull('foto')->get()->each(function ($row) {
            $foto = $row->foto;
            // If it's already a JSON array, leave it alone
            if (!str_starts_with($foto, '[')) {
                DB::table('galeris')->where('id', $row->id)->update([
                    'foto' => json_encode([$foto])
                ]);
            }
        });

        Schema::table('galeris', function (Blueprint $table) {
            $table->json('foto')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->string('foto')->nullable()->change();
        });
    }
};
