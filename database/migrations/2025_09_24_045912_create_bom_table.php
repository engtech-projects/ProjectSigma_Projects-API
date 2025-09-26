<?php

use App\Enums\SourceType;
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
        Schema::create('boms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDeleteRestrict()->onUpdateRestrict();
            $table->foreignId('task_id')->nullable()->constrained()->onDeleteRestrict()->onUpdateRestrict();
            $table->foreignId('resource_id')->nullable()->constrained()->onDeleteRestrict()->onUpdateRestrict();
            $table->string('material_name', 255);
            $table->decimal('quantity', 10, 2);
            $table->string('unit', 50);
            $table->decimal('unit_price', 15, 2)->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->text('additional_details')->nullable();
            $table->enum('source_type', SourceType::toArray());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boms');
    }
};
