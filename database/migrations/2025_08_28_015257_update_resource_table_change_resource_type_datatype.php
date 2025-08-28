<?php

use App\Enums\ResourceType;
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
            $allowed = ResourceType::toArray();
            $enumList = "'" . implode("','", $allowed) . "'";
            if (Schema::hasColumn('resources', 'resource_type')) {
                DB::table('resources')->whereNotIn('resource_type', $allowed)->update(['resource_type' => null]);
                DB::statement("ALTER TABLE `resources` MODIFY COLUMN `resource_type` ENUM({$enumList}) NULL AFTER `task_id`");
            } else {
                Schema::table('resources', function(Blueprint $table) use ($allowed) {
                    $table->enum('resource_type', $allowed)->nullable()->after('task_id');
                });
            }
        });
        $mapping = [
            1 => ResourceType::MATERIALS,
            2 => ResourceType::LABOR_EXPENSE,
            3 => ResourceType::EQUIPMENT_RENTAL,
            4 => ResourceType::MISCELLANEOUS_COST,
            5 => ResourceType::OTHER_EXPENSES,
        ];
        foreach ($mapping as $nameId => $enumValue) {
            DB::table('resources')
                ->where('name_id', $nameId)
                ->update([
                    'resource_type' => $enumValue
                ]);
        }
        $foreignKeyName = DB::table('information_schema.KEY_COLUMN_USAGE')
        ->where('TABLE_NAME', 'resources')
        ->where('COLUMN_NAME', 'name_id')
        ->where('TABLE_SCHEMA', DB::getDatabaseName())
        ->value('CONSTRAINT_NAME');
        if ($foreignKeyName) {
            DB::statement("ALTER TABLE resources DROP FOREIGN KEY `{$foreignKeyName}`");
        }
        Schema::table('resources', function (Blueprint $table) {
            if (Schema::hasColumn('resources', 'name_id')) {
                $table->dropColumn('name_id');
            }
        });
        Schema::dropIfExists('resource_names');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table){
            $table->dropColumn('resource_type');
        });
    }
};
