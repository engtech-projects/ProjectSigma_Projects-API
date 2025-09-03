<?php

use App\Enums\ProjectStatus;
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
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', ProjectStatus::toArray())
                ->default('pending')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', array_merge(ProjectStatus::toArray(), ['open', 'submitted', 'approved', 'archived', 'cancelled', 'void', 'deleted', 'draft', 'myProjects']))
                ->default('open')
                ->change();
        });
    }
};
