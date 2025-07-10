<?php

use App\Enums\MarketingStage;
use App\Enums\TssStage;
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
        // Step 1: Add enum columns using values from the Enums
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('marketing_stage', MarketingStage::values())->nullable()->after('stage');
            $table->enum('tss_stage', TssStage::values())->nullable()->after('marketing_stage');
        });

        // Step 2: Migrate data based on old 'stage' column
        DB::table('projects')->select('id', 'stage')->chunkById(100, function ($projects) {
            foreach ($projects as $project) {
                $marketing_stage = null;
                $tss_stage = null;

                switch ($project->stage) {
                    case MarketingStage::DRAFT->value:
                    case MarketingStage::PROPOSAL->value:
                    case MarketingStage::BIDDING->value:
                        $marketing_stage = $project->stage;
                        $tss_stage = TssStage::PENDING->value;
                        break;

                    case MarketingStage::AWARDED->value:
                        $marketing_stage = MarketingStage::AWARDED->value;
                        $tss_stage = TssStage::AWARDED->value;
                        break;

                    case TssStage::ARCHIVED->value:
                        $marketing_stage = MarketingStage::AWARDED->value;
                        $tss_stage = TssStage::ARCHIVED->value;
                        break;
                }

                DB::table('projects')
                    ->where('id', $project->id)
                    ->update([
                        'marketing_stage' => $marketing_stage,
                        'tss_stage' => $tss_stage,
                    ]);
            }
        });

        // Step 3: Alter columns to be NOT NULL now that all values are filled
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('marketing_stage', MarketingStage::values())->nullable(false)->change();
            $table->enum('tss_stage', TssStage::values())->nullable(false)->change();
        });

        // Step 4: Drop the old 'stage' column
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('stage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Re-add the old 'stage' column
        Schema::table('projects', function (Blueprint $table) {
            $table->string('stage')->nullable()->after('tss_stage');
        });

        // Step 2: Rebuild the 'stage' column based on the enums
        DB::table('projects')->select('id', 'marketing_stage', 'tss_stage')->chunkById(100, function ($projects) {
            foreach ($projects as $project) {
                $stage = null;

                switch ($project->marketing_stage) {
                    case MarketingStage::DRAFT->value:
                    case MarketingStage::PROPOSAL->value:
                    case MarketingStage::BIDDING->value:
                        $stage = $project->marketing_stage;
                        break;

                    case MarketingStage::AWARDED->value:
                        $stage = $project->tss_stage === TssStage::ARCHIVED->value
                            ? TssStage::ARCHIVED->value
                            : MarketingStage::AWARDED->value;
                        break;
                }

                DB::table('projects')
                    ->where('id', $project->id)
                    ->update(['stage' => $stage]);
            }
        });

        // Step 3: Drop the enum columns
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('marketing_stage');
            $table->dropColumn('tss_stage');
        });
    }
};
