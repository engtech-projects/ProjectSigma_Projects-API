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
        Schema::create('project_schedule', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('task_id');
            $table->integer('activity_no');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('date_completed');
            $table->string('status');
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('restrict');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_schedule');
    }
};
