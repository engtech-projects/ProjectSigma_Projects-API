<?php

use App\Enums\ResourceNamesCategory;
use App\Enums\ResourceType;
use Database\Seeders\ResourceNamesTableSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('resources', function (Blueprint $table){
            if (!Schema::hasColumn('resources', 'resource_type')) {
                $table->string('resource_type')->nullable()->after('task_id');
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
        Schema::table('resources', function (Blueprint $table){
            if (Schema::hasColumn('resources', 'name_id')) {
                $table->dropColumn('name_id');
                if (!Schema::hasColumn('resources', 'resource_type')) {
                    $table->string('resource_type')->required()->after('task_id');
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
        Schema::dropIfExists('resource_names');
        Schema::create('resource_names', function (Blueprint $table){
                $table->id();
                $table->string('name');
                $table->enum('category', ResourceNamesCategory::toArray());
                $table->string('description')->nullable();
                $table->timestamps();
        });
        Schema::table('resources', function (Blueprint $table) {
            if (!Schema::hasColumn('resources', 'name_id')) {
                $table->integer('name_id')->nullable()->after('task_id');
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
            if (Schema::hasColumn('resources', 'resource_type')) {
                $table->dropColumn('resource_type');
            }
        });
    }
};
