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
		Schema::create('tasks', function (Blueprint $table) {
            $table->id();
			$table->foreignId('phase_id')->constrained('phases')->onDelete('cascade');
			$table->string('name');
			$table->text('description');
			$table->decimal('quantity', 10, 2);
			$table->string('unit');
			$table->decimal('unit_price', 10, 2);
			$table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
