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
        //Change columns orders
        DB::statement('ALTER TABLE projects MODIFY created_at TIMESTAMP NULL AFTER current_revision_id');
        DB::statement('ALTER TABLE projects MODIFY updated_at TIMESTAMP NULL AFTER created_at');
        DB::statement('ALTER TABLE projects MODIFY deleted_at TIMESTAMP NULL AFTER updated_at');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
