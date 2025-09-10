<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('setup_document_signatures', function (Blueprint $table) {
            Schema::table('setup_document_signatures', function (Blueprint $table) {
                $table->enum('signatory_source', ['internal', 'external'])
                    ->default('external')
                    ->after('document_type');
            });
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setup_document_signatures', function (Blueprint $table) {
            Schema::table('setup_document_signatures', function (Blueprint $table) {
                $table->dropColumn('signatory_source');
            });
        });
    }
};
