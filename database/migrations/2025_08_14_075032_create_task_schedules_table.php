<?php

use App\Enums\TaskStatus;
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
        Schema::create('task_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('item_id')
                ->constrained('tasks')
                ->OnDelete('restrict');
            $table->date('original_start');
            $table->date('original_end');
            $table->date('current_start')->nullable();
            $table->date('current_end')->nullable();
            $table->integer('duration_days')->nullable();
            $table->decimal('weight_percent')->nullable();
            $table->enum('status', TaskStatus::values());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_schedules');
    }
};
