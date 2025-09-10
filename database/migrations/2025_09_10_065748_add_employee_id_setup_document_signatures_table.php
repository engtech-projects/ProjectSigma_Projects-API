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
            if (!Schema::hasColumn('setup_document_signatures', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('signatory_source');
            }
            if (!Schema::hasColumn('setup_document_signatures', 'signature_label')) {
                $table->string('signature_label')->nullable()->after('user_id');
            }
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setup_document_signatures', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('signature_label');
        });
    }
};
