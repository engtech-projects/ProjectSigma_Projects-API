<?php

use App\Enums\TimelineClassification;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('task_schedules', function (Blueprint $table) {
            $table->date('start_date')->after('item_id');
            $table->date('end_date')->after('start_date');
            $table->enum('timeline_classification', TimelineClassification::values())->default('current_timeline')->after('id');
            $table->dropColumn('original_start');
            $table->dropColumn('original_end');
            $table->dropColumn('current_start');
            $table->dropColumn('current_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_schedules', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('timeline_classification');
            $table->date('original_start')->nullable();
            $table->date('original_end')->nullable();
            $table->date('current_start')->nullable();
            $table->date('current_end')->nullable();
        });
    }
};
