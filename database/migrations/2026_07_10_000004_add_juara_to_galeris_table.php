<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->unsignedTinyInteger('juara')->nullable()->after('kategori');
            // nilai: 1 = Juara 1, 2 = Juara 2, 3 = Juara 3, dst
            // null = bukan kategori prestasi / tidak ada juara
        });
    }

    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->dropColumn('juara');
        });
    }
};
