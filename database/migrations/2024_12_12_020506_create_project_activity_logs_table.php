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
        Schema::create('project_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('task_id');
            $table->date('log_date');
            $table->decimal('quantity', 15, 2);
            $table->text('remarks');
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
        Schema::dropIfExists('project_activity_logs');
    }
};
