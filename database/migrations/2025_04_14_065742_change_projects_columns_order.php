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
        // Change columns order in projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('current_revision_id')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Change columns order in projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->id()->first()->change();
            $table->char('uuid', 36)->unique()->after('id')->change();
            $table->bigInteger('parent_project_id')->nullable()->after('uuid')->change();
            $table->string('contract_id')->after('parent_project_id')->change();
            $table->string('code')->after('contract_id')->change();
            $table->string('name')->after('code')->change();
            $table->tinyText('location')->after('name')->change();
            $table->decimal('amount', 15, 2)->after('location')->change();
            $table->string('duration')->after('amount')->change();
            $table->string('nature_of_work')->after('duration')->change();
            $table->date('contract_date')->after('nature_of_work')->change();
            $table->date('ntp_date')->after('contract_date')->change();
            $table->date('noa_date')->after('ntp_date')->change();
            $table->string('license')->after('noa_date')->change();
            $table->tinyInteger('is_original')->default(1)->after('license')->change();
            $table->decimal('version', 2, 1)->default(1.0)->after('is_original')->change();
            $table->string('status')->default('open')->after('version')->change();
            $table->string('stage')->default('proposal')->after('status')->change();
            $table->string('project_identifier')->after('stage')->change();
            $table->string('implementing_office')->after('project_identifier')->change();
            $table->char('current_revision_id', 36)->nullable()->after('implementing_office')->change();
            $table->timestamp('created_at')->nullable()->after('current_revision_id')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }
};
