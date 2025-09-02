<?php

use App\Enums\MarketingStage;
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
        DB::table('projects')
            ->where('marketing_stage', 'generate_to_tss')
            ->update([
                'marketing_stage' => MarketingStage::AWARDED->value,
            ]);
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('marketing_stage', MarketingStage::toArray())
                ->default('draft')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('marketing_stage', MarketingStage::toArray())
                ->default('draft')
                ->change();
        });
    }
};
