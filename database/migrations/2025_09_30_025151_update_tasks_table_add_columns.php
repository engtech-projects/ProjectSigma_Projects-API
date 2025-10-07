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
        Schema::table('tasks', function (Blueprint $table) {
            $table->decimal('draft_unit_price', 15, 2)->nullable()->after('unit_price');
            $table->decimal('draft_amount', 15, 2)->nullable()->after('draft_unit_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('draft_unit_price');
            $table->dropColumn('draft_amount');
        });
    }
};
