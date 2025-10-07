<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
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
        DB::statement("
            ALTER TABLE resources
            MODIFY quantity DECIMAL(10,2) NULL,
            MODIFY unit_count DECIMAL(10,2) NULL,
            MODIFY unit_cost DECIMAL(10,2) NULL,
            MODIFY total_cost DECIMAL(10,2) NULL
        ");
    }
};
