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
            $table->enum('timeline_classification', TimelineClassification::values())->default('current timeline')->after('id');
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
            $table->dropColumn('timeline_classification');
            $table->date('original_start');
            $table->date('original_end');
            $table->date('current_start');
            $table->date('current_end');
        });
    }
};
