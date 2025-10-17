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
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_id')->after('user_id')->nullable();
            $table->string('type')->after('employee_id');
            $table->json('accessibilities')->after('type')->nullable();
            $table->dropColumn('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('employee_id');
            $table->dropColumn('type');
            $table->dropColumn('accessibilities');
            $table->string('uuid')->nullable()->after('user_id');
        });
    }
};
