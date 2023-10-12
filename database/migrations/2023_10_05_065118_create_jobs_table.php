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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('position_no')->unique();
            $table->string('hr_spoc');
            $table->string('business_spoc');
            $table->integer('state_id');
            $table->integer('location_id');
            $table->integer('industry_id');
            $table->integer('company_id');
            $table->integer('sales_non_sales_id');
            $table->integer('department_id');
            $table->integer('channel_id');
            $table->string('designation_id');
            $table->integer('level_id');
            $table->integer('product_id');
            $table->integer('openings');
            $table->bigInteger('ctc_from');
            $table->bigInteger('ctc_to');
            $table->longText('job_description')->nullable();
            $table->text('attach_job_description')->nullable();
            $table->enum('status', ['open', 'close'])->default('open');
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
