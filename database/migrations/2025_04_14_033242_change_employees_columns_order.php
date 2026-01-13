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
        // Change columns order in employees table
        Schema::table('employees', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('family_name')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Change columns order in employees table
        Schema::table('employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->after('id')->change();
            $table->string('first_name')->after('employee_id')->change();
            $table->string('middle_name')->nullable()->after('first_name')->change();
            $table->string('family_name')->after('middle_name')->change();
            $table->timestamp('created_at')->nullable()->after('family_name')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }
};
