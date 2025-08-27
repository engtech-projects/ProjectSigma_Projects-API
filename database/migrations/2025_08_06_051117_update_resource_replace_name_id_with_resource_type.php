<?php

use App\Enums\ResourceNamesCategory;
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
            $table->enum('resource_type', ResourceType::toArray())->nullable()->after('task_id');
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
                if (!Schema::hasColumn('resources', 'resource_type')) {
                    $table->string('resource_type')->after('task_id');
                }
            }
        });
        Schema::dropIfExists('resource_names');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('resource_names')) {
            Schema::create('resource_names', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->enum('category', ResourceNamesCategory::toArray())->nullable();
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }
        DB::table('resource_names')->insert([
            ['id' => 1, 'name' => 'materials', 'category' => ResourceNamesCategory::INVENTORY, 'description' => null],
            ['id' => 2, 'name' => 'labor_expense', 'category' => ResourceNamesCategory::SERVICE, 'description' => null],
            ['id' => 3, 'name' => 'equipment_rental', 'category' => ResourceNamesCategory::INVENTORY, 'description' => null],
            ['id' => 4, 'name' => 'miscellaneous_cost', 'category' => ResourceNamesCategory::INVENTORY, 'description' => null],
            ['id' => 5, 'name' => 'other_expenses', 'category' => ResourceNamesCategory::INVENTORY, 'description' => null],
        ]);
        Schema::table('resources', function (Blueprint $table) {
            if (!Schema::hasColumn('resources', 'name_id')) {
                $table->unsignedBigInteger('name_id')->nullable()->after('task_id');
            }
        });
        $reverseMapping = [
            'materials' => 1,
            'labor_expense' => 2,
            'equipment_rental' => 3,
            'miscellaneous_cost' => 4,
            'other_expenses' => 5,
        ];
        foreach ($reverseMapping as $enumValue => $nameId) {
            DB::table('resources')
                ->where('resource_type', $enumValue)
                ->update(['name_id' => $nameId]);
        }
        Schema::table('resources', function (Blueprint $table) {
            $table->foreign('name_id')->references('id')->on('resource_names')->onDelete('restrict');
        });
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn('resource_type');
        });
    }
};
