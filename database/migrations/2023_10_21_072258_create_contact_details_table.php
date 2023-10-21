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
        Schema::create('contact_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_no', 20);
            $table->string('alternate_contact_no', 20)->nullable();
            $table->string('email', 100);
            $table->foreignId('industry_id')->constrained();
            $table->foreignId('company_id')->constrained();
            $table->foreignId('sales_non_sales_id')->constrained();
            $table->foreignId('department_id')->constrained();
            $table->foreignId('channel_id')->constrained();
            $table->foreignId('state_id')->constrained();
            $table->foreignId('location_id')->constrained();
            $table->text('address');
            $table->string('reporting_manager_name')->nullable();
            $table->string('reporting_contact_no', 20)->nullable();
            $table->string('reporting_email', 100)->nullable();
            $table->text('reporting_location')->nullable();
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
        Schema::dropIfExists('contact_details');
    }
};
