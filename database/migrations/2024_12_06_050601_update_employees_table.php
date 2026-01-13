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
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('family_name');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('employee_id');
            $table->dropColumn('first_name');
            $table->dropColumn('middle_name');
            $table->dropColumn('family_name');
            $table->dropSoftDeletes();
        });
    }
};
