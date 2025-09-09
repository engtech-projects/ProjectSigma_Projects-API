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
        Schema::table('resources', function (Blueprint $table){
            $table->unsignedBigInteger('setup_item_profile_id')
                ->nullable()
                ->after('task_id');
            $table->foreign('setup_item_profile_id')
                ->references('id')
                ->on('setup_item_profile')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table){
            $table->dropForeign(['setup_item_profile_id']);
            $table->dropColumn('setup_item_profile_id');
        });
    }
};
