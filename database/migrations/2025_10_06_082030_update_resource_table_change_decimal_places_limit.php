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
        Schema::table('resources', function (Blueprint $table) {
            $table->decimal('quantity', 20, 8)->nullable()->change();
            $table->decimal('unit_count', 20, 8)->nullable()->change();
            $table->decimal('unit_cost', 20, 8)->nullable()->change();
            $table->decimal('total_cost', 20, 8)->nullable()->change();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->decimal('quantity', 10, 2)->nullable()->change();
            $table->decimal('unit_count', 10, 2)->nullable()->change();
            $table->decimal('unit_cost', 10, 2)->nullable()->change();
            $table->decimal('total_cost', 10, 2)->nullable()->change();
        });
    }
};
