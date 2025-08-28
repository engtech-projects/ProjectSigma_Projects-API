<?php

use App\Enums\ResourceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $allowed = ResourceType::toArray();
            $enumList = "'" . implode("','", $allowed) . "'";
            if (Schema::hasColumn('resources', 'resource_type')) {
                DB::table('resources')->whereNotIn('resource_type', $allowed)->update(['resource_type' => null]);
                DB::statement("ALTER TABLE `resources` MODIFY COLUMN `resource_type` ENUM({$enumList}) NULL AFTER `task_id`");
            } else {
                Schema::table('resources', function (Blueprint $table) use ($allowed) {
                    $table->enum('resource_type', $allowed)->nullable()->after('task_id');
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->string('resource_type');
        });
    }
};
