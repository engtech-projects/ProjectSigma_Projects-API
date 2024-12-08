<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ProjectStatus;
use App\Enums\ProjectStage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('parent_project_id')->nullable();
			$table->string('contract_id');
			$table->string('code')->nullable()->unique();
			$table->string('name');
            $table->tinyText('location');
			$table->decimal('amount', 15, 2);
			$table->string('duration')->nullable();
			$table->string('nature_of_work')->nullable();
			$table->date('contract_date')->nullable();
			$table->date('ntp_date')->nullable();
			$table->date('noa_date')->nullable();
			$table->string('license')->nullable();
			$table->boolean('is_original')->index()->default(true);
			$table->string('version')->default('v1.0');
			$table->string('status')->index()->default(ProjectStatus::OPEN);
			$table->string('stage')->index()->default(ProjectStage::PROPOSAL);
			$table->foreign('parent_project_id')->references('id')->on('projects');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
