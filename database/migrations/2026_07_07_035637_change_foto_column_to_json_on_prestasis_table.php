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
        // Convert existing string values to valid JSON arrays first
        DB::table('prestasis')->whereNotNull('foto')->get()->each(function ($row) {
            $foto = $row->foto;
            if (!str_starts_with($foto, '[')) {
                DB::table('prestasis')->where('id', $row->id)->update([
                    'foto' => json_encode([$foto])
                ]);
            }
        });

        // PostgreSQL requires explicit USING clause to cast text -> json
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE prestasis ALTER COLUMN foto TYPE json USING foto::json');
        } else {
            Schema::table('prestasis', function (Blueprint $table) {
                $table->json('foto')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE prestasis ALTER COLUMN foto TYPE text USING foto::text');
        } else {
            Schema::table('prestasis', function (Blueprint $table) {
                $table->string('foto')->nullable()->change();
            });
        }
    }
};
