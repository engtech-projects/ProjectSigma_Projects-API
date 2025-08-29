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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('abc')->nullable()->after('id'); // or after any relevant column
            $table->date('bid_date')->nullable()->after('abc');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->decimal('unit_price', 15, 2)->nullable()->change();
            $table->decimal('amount', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['abc', 'bid_date']);
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->decimal('unit_price', 15, 2)->nullable(false)->change();
            $table->decimal('amount', 15, 2)->nullable(false)->change();
        });
    }
};
