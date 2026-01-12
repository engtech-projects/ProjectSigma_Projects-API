<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('boms', function (Blueprint $table) {
            $table->decimal('original_quantity', 20, 8)->nullable();
            $table->decimal('revised_quantity', 20, 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boms', function (Blueprint $table) {
            $table->dropColumn('original_quantity');
            $table->dropColumn('revised_quantity');
        });
    }
};
