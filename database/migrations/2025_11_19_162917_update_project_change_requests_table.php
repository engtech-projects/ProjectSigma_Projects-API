<?php
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
        Schema::table('project_change_requests', function (Blueprint $table) {
            if (Schema::hasColumn('project_change_requests', 'resource_type')) {
                $table->dropColumn('resource_type');
            }
        });
        // 2. Update ENUM values for request_type using raw SQL
        DB::statement("ALTER TABLE `project_change_requests`
            MODIFY `request_type` ENUM('scope_change','deadline_extension','budget_adjustment','directcost_approval_request') NOT NULL");
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_change_requests', function (Blueprint $table) {
            $table->string('resource_type')->nullable();
        });
        DB::statement("ALTER TABLE `project_change_requests`
            MODIFY `request_type` ENUM('scope_change','deadline_extension','budget_adjustment') NOT NULL");
    }
};
