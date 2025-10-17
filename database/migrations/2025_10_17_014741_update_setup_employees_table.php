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
        Schema::table('setup_employees', function (Blueprint $table) {
            $table->string('current_position')->nullable()->after('height');
            $table->text('digital_signature')->nullable()->after('current_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setup_employees', function (Blueprint $table) {
            $table->dropColumn([
                'current_position',
                'digital_signature',
            ]);
        });
    }
};
