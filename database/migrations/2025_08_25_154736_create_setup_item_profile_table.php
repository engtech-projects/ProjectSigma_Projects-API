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
        Schema::dropIfExists('item_profiles');
        Schema::create('setup_item_profile', function (Blueprint $table) {
            $table->id();

            $table->string('item_code')->unique();
            $table->string('item_description')->nullable();
            $table->string('thickness')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('outside_diameter')->nullable();
            $table->string('inside_diameter')->nullable();
            $table->string('angle')->nullable();
            $table->string('size')->nullable();
            $table->string('specification')->nullable();
            $table->string('volume')->nullable();
            $table->string('weight')->nullable();
            $table->string('grade')->nullable();
            $table->string('volts')->nullable();
            $table->string('plates')->nullable();
            $table->string('part_number')->nullable();
            $table->string('color')->nullable();
            $table->unsignedBigInteger('uom')->nullable();
            $table->foreign('uom')->references('id')->on('uom')->onDelete('set null');
            $table->double('uom_conversion_value', 8, 2)->nullable();
            $table->string('item_group')->nullable();
            $table->string('sub_item_group')->nullable();
            $table->enum('inventory_type', ['Inventoriable', 'Non-Inventoriable'])->nullable();
            $table->enum('active_status', ['Active', 'Inactive'])->default('active');
            $table->boolean('is_approved')->default(false);
            $table->softDeletes();
            $table->timestamps();
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
