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
        Schema::create('cashflow_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashflow_id')
                ->constrained("cashflows")
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreignId('item_id')
                ->constrained('resources')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->unique(['cashflow_id', 'item_id']);
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cashflow_items', function (Blueprint $table) {
            $table->dropForeign(['cashflow_id']);
            $table->dropForeign(['item_id']);
        });
        Schema::dropIfExists('cashflow_items');
    }
};
