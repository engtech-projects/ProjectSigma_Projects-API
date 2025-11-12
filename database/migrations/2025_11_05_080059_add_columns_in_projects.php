<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('project_classification')->nullable();
            $table->string('project_limits')->nullable();
            $table->string('project_manager')->nullable();
            $table->string('project_engineer')->nullable();
            $table->string('foreman_leadman')->nullable();
            $table->string('materials_engineer')->nullable();
            $table->string('safety_officer')->nullable();
            $table->string('project_management_specialist')->nullable();
            $table->json('equipment_deployed')->nullable();
            $table->json('suppliers')->nullable();
            $table->json('subcontractors')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('project_classification');
            $table->dropColumn('project_limits');
            $table->dropColumn('project_manager');
            $table->dropColumn('project_engineer');
            $table->dropColumn('foreman_leadman');
            $table->dropColumn('materials_engineer');
            $table->dropColumn('safety_officer');
            $table->dropColumn('project_management_specialist');
            $table->dropColumn('equipment_deployed');
            $table->dropColumn('suppliers');
            $table->dropColumn('subcontractors');
        });
    }
};
