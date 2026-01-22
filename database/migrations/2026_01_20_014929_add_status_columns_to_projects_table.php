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
            $table->enum('directcost_status', [
                'Approved',
                'Pending',
                'Ongoing',
                'Denied',
                'Voided',
                'Revised'
            ])->default('Pending')->after('id');
            $table->enum('bom_status', [
                'Approved',
                'Pending',
                'Ongoing',
                'Denied',
                'Voided',
                'Revised'
            ])->default('Pending')->after('directcost_status');
            $table->enum('schedule_status', [
                'Approved',
                'Pending',
                'Ongoing',
                'Denied',
                'Voided',
                'Revised'
            ])->default('Pending')->after('bom_status');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'directcost_status',
                'bom_status',
                'schedule_status',
            ]);
        });
    }
};
