<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notas', function (Blueprint $table) {
            $table->boolean('is_void')->default(false)->after('catatan');
            $table->timestamp('voided_at')->nullable()->after('is_void');
        });
    }

    public function down(): void
    {
        Schema::table('notas', function (Blueprint $table) {
            $table->dropColumn(['is_void', 'voided_at']);
        });
    }
};