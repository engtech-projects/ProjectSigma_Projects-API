<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('resources', function (Blueprint $table){
            $table->decimal('consumption_rate', 10, 2)->after('total_cost');
            $table->varchar('consumption_unit', 50)->after('consumption_rate');
            $table->enum('labor_cost_category', ['13th_month', 'government_premiums', 'project_allowance'])->after('consumption_rate');
            $table->enum('work_time_category', ['regular', 'overtime', 'sunday', 'regular_holiday', 'special_holiday'])->after('labor_cost_category');
            $table->text('remarks')->after('work_time_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table){
            $table->dropColumn('consumption_rate');
            $table->dropColumn('consumption_unit');
            $table->dropColumn('labor_cost_category');
            $table->dropColumn('work_time_category');
            $table->dropColumn('remarks');
        });
    }
};
