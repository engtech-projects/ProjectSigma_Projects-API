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
        DB::table('projects')
        ->whereNotNull('abc')
        ->where('abc', '!=', '')
        ->whereRaw("abc NOT REGEXP '^-?[0-9]+(\\.[0-9]+)?$'")
        ->update(['abc' => '0']);
        DB::statement("UPDATE projects SET abc = CAST(abc AS DECIMAL(20,8)) WHERE abc REGEXP '^-?[0-9]+(\\.[0-9]+)?$'");
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('abc', 20, 8)->nullable()->change();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            UPDATE projects
            SET abc = CAST(abc AS CHAR)
            WHERE abc IS NOT NULL
        ");
        Schema::table('projects', function (Blueprint $table) {
            $table->string('abc')->nullable()->change();
        });
    }
};
