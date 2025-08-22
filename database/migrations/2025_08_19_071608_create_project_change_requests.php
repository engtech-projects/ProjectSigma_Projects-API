<?php

use App\Enums\ChangeRequestStatus;
use App\Enums\ChangeRequestType;
use App\Enums\RequestStatuses;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('requested_by')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
            $table->enum('request_type', ChangeRequestType::values());
            $table->json('changes');
            $table->json('approvals');
            $table->enum('request_status', RequestStatuses::values());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_change_requests');
    }
};
