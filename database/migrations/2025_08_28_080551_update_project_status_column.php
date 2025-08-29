<?php

use App\Enums\NewProjectStatus;
use App\Enums\ProjectStatus;
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
        $allowed = NewProjectStatus::toArray();
        $list = "'".implode("', '", $allowed)."'";
        $mapping = [
            'open' => 'pending',
            'draft' => 'pending',
            'proposal' => 'ongoing',
            'bidding' => 'ongoing',
            'closed' => 'completed',
        ];
        foreach ($mapping as $old => $new) {
            DB::table('projects')
                ->where('status', $old)
                ->update([
                    'status' => $new,
                ]);
        }
        DB::statement("ALTER TABLE projects CHANGE status status ENUM($list) DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       if (!Schema::hasColumn('projects', 'status')) {
           Schema::table('projects', function (Blueprint $table) {
               $table->enum('status', ProjectStatus::toArray())->default('open');
           });
       }
    }
};
