<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        // 1. Add a temporary column to keep old values
        Schema::table('resources', function (Blueprint $table) {
            $table->string('old_resource_type')->nullable()->after('resource_type');
        });
        // 2. Copy data from resource_type -> old_resource_type
        DB::table('resources')->update([
            'old_resource_type' => DB::raw('resource_type'),
        ]);
        // 3. Drop the old enum column
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn('resource_type');
        });
        // 4. Recreate the enum with new values
        Schema::table('resources', function (Blueprint $table) {
            $table->enum('resource_type', [
                'materials',
                'labor_expense',
                'equipment_rental',
                'government_premiums',
                'project_allowance',
                'miscellaneous_cost',
                'other_expenses',
                'fuel_oil_cost',
                'overhead_cost',
            ])->nullable()->after('old_resource_type');
        });
        // 5. Copy data back into the new enum column
        DB::table('resources')->update([
            'resource_type' => DB::raw('old_resource_type'),
        ]);
        // 6. Drop the temporary column
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn('old_resource_type');
        });
    }
    public function down(): void
    {
        // 1. Add a temporary column to keep current values
        Schema::table('resources', function (Blueprint $table) {
            $table->string('old_resource_type')->nullable()->after('resource_type');
        });
        // 2. Copy data from resource_type -> old_resource_type
        DB::table('resources')->update([
            'old_resource_type' => DB::raw('resource_type'),
        ]);
        // 3. Drop the new enum column
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn('resource_type');
        });
        // 4. Recreate the *original* enum definition (7 values only)
        Schema::table('resources', function (Blueprint $table) {
            $table->enum('resource_type', [
                'materials',
                'labor_expense',
                'equipment_rental',
                'miscellaneous_cost',
                'other_expenses',
                'fuel_oil_cost',
                'overhead_cost',
            ])->nullable()->after('old_resource_type');
        });
        // 5. Copy data back into the restored enum column (invalid -> NULL)
        DB::table('resources')->update([
            'resource_type' => DB::raw("
                CASE
                    WHEN old_resource_type IN (
                        'materials',
                        'labor_expense',
                        'equipment_rental',
                        'miscellaneous_cost',
                        'other_expenses',
                        'fuel_oil_cost',
                        'overhead_cost'
                    ) THEN old_resource_type
                    ELSE NULL
                END
            "),
        ]);
        // 6. Drop the temporary column
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn('old_resource_type');
        });
    }
};
?>
