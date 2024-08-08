<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('project_code')
                ->after('id');
            $table->string('project_identifier')
                ->after('project_code');
            $table->double('contract_amount')
                ->after('contract_location');
            $table->string('contract_duration')
                ->after('contract_amount');
            $table->string('implementing_office')
                ->after('contract_duration');
            $table->string('nature_of_work')
                ->after('implementing_office');
            $table->date('date_of_noa')
                ->after('nature_of_work');
            $table->date('date_of_contract')
                ->after('date_of_noa');
            $table->date('date_of_ntp')
                ->after('date_of_contract');
            $table->string('license')
                ->after('date_of_contract');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('project_code');
            $table->dropColumn('project_identifier');
            $table->dropColumn('contract_amount');
            $table->dropColumn('contract_duration');
            $table->dropColumn('implementing_office');
            $table->dropColumn('nature_of_work');
            $table->dropColumn('date_of_noa');
            $table->dropColumn('date_of_contract');
            $table->dropColumn('date_of_ntp');
            $table->dropColumn('license');
        });
    }
};
