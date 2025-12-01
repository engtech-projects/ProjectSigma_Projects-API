<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('tss_status', ['Approved', 'Pending', 'Ongoing', 'Denied', 'Voided'])
            ->nullable()
            ->after('version');
        });
        DB::table('projects')
            ->where('tss_stage', 'dupa_preparation')
            ->update(['tss_status' => 'Pending']);
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('tss_status');
        });
    }
};
