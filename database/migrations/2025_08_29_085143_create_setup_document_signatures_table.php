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
        Schema::create('setup_document_signatures', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('position', 255);
            $table->text('license');
            $table->enum('document_type', [
                'bill_of_quantities',
                'detailed_estimates',
                'cash_flow',
                'summary_of_rates',
                'bid_summary'
            ]);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setup_document_signatures');
    }
};
