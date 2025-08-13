<?php

use App\Enums\MarketingStage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('project', function (Blueprint $table) {
            $table->enum('marketing_stage', MarketingStage::values())
                ->default(MarketingStage::DRAFT->value)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $originalValues = array_map(
            fn($case) => $case->value,
            array_filter(MarketingStage::cases(), fn($case) => $case !== MarketingStage::GENERATETOTSS)
        );

        DB::table('projects')
            ->where('marketing_stage', MarketingStage::GENERATETOTSS->value)
            ->update(['marketing_stage' => MarketingStage::AWARDED->value]);

        Schema::table('project', function (Blueprint $table) use ($originalValues) {
            $table->enum('marketing_stage', $originalValues)
                ->default(MarketingStage::DRAFT->value)
                ->change();
        });
    }
};
