<?php

use App\Enums\ProjectStatus;
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
        $mapping = [
            'open' => 'pending',
            'submitted' => 'pending',
            'approved' => 'pending',
            'draft' => 'pending',
            'proposal' => 'pending',
            'bidding' => 'pending',
            'awarded' => 'ongoing',
            'archived' => 'completed',
            'closed' => 'completed',
        ];
        foreach ($mapping as $old => $new) {
            DB::table('projects')
                ->where('status', $old)
                ->update([
                    'status' => $new,
                ]);
        }
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', ProjectStatus::toArray())
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
            $table->enum('status', ['pending', 'ongoing', 'open', 'draft', 'proposal', 'bidding', 'awarded', 'closed'])
                ->default('open')
                ->change();
        });
        $mapping = [
            'pending' => 'open',
            'ongoing' => 'awarded',
        ];
        foreach ($mapping as $old => $new) {
            DB::table('projects')
                ->where('status', $old)
                ->update([
                    'status' => $new,
                ]);
        }
    }
};
