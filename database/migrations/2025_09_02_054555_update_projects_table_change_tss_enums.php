<?php

use App\Enums\TssStage;
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
            $table->enum('tss_stage', array_merge(TssStage::toArray(), ['awarded']))
                ->default('pending')
                ->change();
        });
        DB::table('projects')
            ->where('tss_stage', 'awarded')
            ->update([
                'tss_stage' => TssStage::DUPA_PREPARATION->value,
            ]);
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('tss_stage', TssStage::toArray())
                  ->default('pending')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('tss_stage', ['pending','awarded','archived','dupa_preparation'])
                  ->default('pending')
                  ->change();
        });
        DB::table('projects')
            ->where('tss_stage', TssStage::DUPA_PREPARATION->value)
            ->update([
                'tss_stage' => 'awarded',
        ]);
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('tss_stage', ['pending','awarded','archived'])
                  ->default('pending')
                  ->change();
        });
    }
};
