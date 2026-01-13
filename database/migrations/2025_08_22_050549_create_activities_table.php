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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('item_id')->constrained('tasks')->onDelete('cascade')->onUpdate('cascade');
            $table->string('reference')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->string('schedule')->nullable();
            $table->text('work_description')->nullable();
            $table->decimal('duration', 10, 2);
            $table->decimal('target', 10, 2);
            $table->decimal('actual', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
