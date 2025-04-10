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
        Schema::create('resource_names', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('category', ['inventory', 'service']);
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks');
            $table->foreignId('name_id')->constrained('resource_names');
            $table->text('description');
            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('unit')->nullable();
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->integer('resource_count')->default(1);
            $table->decimal('total_cost', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_names');
        Schema::dropIfExists('resources');
    }
};
