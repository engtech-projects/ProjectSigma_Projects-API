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
        Schema::create('resource_metrics', function (Blueprint $table){
            $table->id();
            $table->foreignId('resource_id')
                ->constrained('resources')
                ->onDelete('cascade');
            $table->string('label', 100);
            $table->decimal('value', 10, 2);
            $table->string('unit', 20);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_metrics');
    }
};
