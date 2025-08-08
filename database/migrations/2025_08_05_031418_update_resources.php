<?php

use App\Enums\LaborCostCategory;
use App\Enums\WorkTimeCategory;
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
            $table->decimal('consumption_rate', 10, 2)->after('total_cost')->nullable();
            $table->string('consumption_unit', 50)->after('consumption_rate')->nullable();
            $table->enum('labor_cost_category', LaborCostCategory::values())->after('consumption_rate')->nullable();
            $table->enum('work_time_category', WorkTimeCategory::values())->after('labor_cost_category')->nullable();
            $table->text('remarks')->after('work_time_category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn('consumption_rate');
            $table->dropColumn('consumption_unit');
            $table->dropColumn('labor_cost_category');
            $table->dropColumn('work_time_category');
            $table->dropColumn('remarks');
        });
    }
};
