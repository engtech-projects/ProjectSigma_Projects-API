<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //Change columns order in users table
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('employee_id')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Change columns order in users table
        Schema::table('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable()->after('id');
            $table->char('uuid', 36)->nullable()->after('user_id')->change();
            $table->string('name')->nullable()->after('uuid')->change();
            $table->string('email')->nullable()->after('name')->change();
            $table->timestamp('email_verified_at')->nullable()->after('email')->change();
            $table->string('password')->nullable()->after('email_verified_at')->change();
            $table->string('remember_token', 100)->nullable()->after('password')->change();
            $table->tinyInteger('is_admin')->default(0)->after('remember_token')->change();
            $table->bigInteger('employee_id')->nullable()->after('is_admin')->change();
            $table->timestamp('created_at')->nullable()->after('employee_id')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }
};
