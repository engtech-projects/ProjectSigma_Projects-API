<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE resources
            MODIFY quantity DECIMAL(20,8) NULL,
            MODIFY unit_count DECIMAL(20,8) NULL,
            MODIFY unit_cost DECIMAL(20,8) NULL,
            MODIFY total_cost DECIMAL(20,8) NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
