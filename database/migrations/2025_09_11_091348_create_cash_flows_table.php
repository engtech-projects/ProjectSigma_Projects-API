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
        Schema::create('cashflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('restrict')->onUpdate('cascade');
            $table->decimal('percent', 10, 2);
            $table->date('date');
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cashflows', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
        });
        Schema::dropIfExists('cashflows');
    }
};
