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
        // Add soft deletes to personal_access_tokens table
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove soft deletes from personal_access_tokens table
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
