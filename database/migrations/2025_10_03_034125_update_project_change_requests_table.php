<?php

use App\Enums\ChangeRequestType;
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
        // Get desired enum values from PHP Enum
        $desiredValues = array_map(
            fn (ChangeRequestType $case) => $case->value,
            ChangeRequestType::cases()
        );
        // 1. Get current column definition from DB
        $currentEnum = DB::selectOne("
            SELECT COLUMN_TYPE as column_type
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'project_change_requests'
              AND COLUMN_NAME = 'resource_type'
        ");
        // 2. If column does not exist → create new one
        if (!$currentEnum) {
            Schema::table('project_change_requests', function (Blueprint $table) use ($desiredValues) {
                $table->enum('resource_type', $desiredValues)->nullable()->after('id');
                // 👆 change "after('id')" if needed
            });
            return;
        }
        // 3. Parse current enum values from COLUMN_TYPE
        preg_match_all("/'([^']+)'/", $currentEnum->column_type, $matches);
        $existingValues = $matches[1] ?? [];
        // 4. If already equal → skip
        if ($existingValues === $desiredValues) {
            return;
        }
        // 5. Otherwise, rebuild column safely
        Schema::table('project_change_requests', function (Blueprint $table) {
            $table->string('old_resource_type')->nullable()->after('resource_type');
        });
        DB::table('project_change_requests')->update([
            'old_resource_type' => DB::raw('resource_type'),
        ]);
        Schema::table('project_change_requests', function (Blueprint $table) {
            $table->dropColumn('resource_type');
        });
        Schema::table('project_change_requests', function (Blueprint $table) use ($desiredValues) {
            $table->enum('resource_type', $desiredValues)->nullable()->after('old_resource_type');
        });
        DB::table('project_change_requests')->update([
            'resource_type' => DB::raw('old_resource_type'),
        ]);
        Schema::table('project_change_requests', function (Blueprint $table) {
            $table->dropColumn('old_resource_type');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
