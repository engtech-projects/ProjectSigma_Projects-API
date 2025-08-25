<?php

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
        Schema::dropIfExists('item_profiles');
        Schema::create('setup_item_profile', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();
            $table->string('item_description')->nullable();
            $table->decimal('thickness', 10, 2)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('outside_diameter', 10, 2)->nullable();
            $table->decimal('inside_diameter', 10, 2)->nullable();
            $table->decimal('angle', 10, 2)->nullable();
            $table->string('size')->nullable();
            $table->string('specification')->nullable();
            $table->decimal('volume', 15, 4)->nullable();
            $table->decimal('weight', 15, 4)->nullable();
            $table->string('grade')->nullable();
            $table->integer('volts')->nullable();
            $table->integer('plates')->nullable();
            $table->string('part_number')->nullable();
            $table->string('color')->nullable();
            $table->string('uom')->nullable(); // unit of measure
            $table->decimal('uom_conversion_value', 15, 4)->nullable();
            $table->string('item_group')->nullable();
            $table->string('sub_item_group')->nullable();
            $table->string('inventory_type')->nullable();
            $table->boolean('active_status')->default(true);
            $table->boolean('is_approved')->default(false);

            $table->softDeletes(); // softdelete support
            $table->timestamps();  // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setup_item_profile');
    }
};
