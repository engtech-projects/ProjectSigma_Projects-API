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
            $table->string('designator')->nullable()->after('designation');
        });

        DB::table('projects')->update([
            'designator' => DB::raw('designation')
        ]);

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('designation');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('position')->nullable()->after('designator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('position');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('designation')->nullable();
        });

        DB::table('projects')->update([
            'designation' => DB::raw('designator')
        ]);

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('designator');
        });
    }
};
