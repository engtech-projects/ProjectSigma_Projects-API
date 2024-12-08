<?php

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
        Schema::table('projects', function($table) {
            $table->string('project_identifier')->nullable();
            $table->string('implementing_office')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function($table) {
            $table->dropColumn('project_identifier');
            $table->dropColumn('implementing_office');
        });
    }
};
