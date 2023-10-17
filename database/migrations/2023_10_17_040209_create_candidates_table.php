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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->references('id')->on('users');
            $table->string('candidate_id', 20);
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('gender', 20);
            $table->string('dob', 70);
            $table->string('mobile', 20);
            $table->string('alternate_mobile', 20)->nullable();
            $table->string('email', 80);
            $table->string('alternate_email', 80)->nullable();
            $table->foreignId('high_qualification_id')->constrained();
            $table->foreignId('state_id')->constrained();
            $table->foreignId('location_id')->constrained();
            $table->foreignId('industry_id')->constrained();
            $table->foreignId('company_id')->constrained();
            $table->foreignId('sales_non_sales_id')->constrained();
            $table->foreignId('department_id')->constrained();
            $table->foreignId('channel_id')->constrained();
            $table->string('designation');
            $table->foreignId('level_id')->constrained();
            $table->integer('experience');
            $table->integer('current_ctc');
            $table->string('pan_no', 50);
            $table->string('employment_status', 50);
            $table->enum('resume_status', ['available', 'attached'])->default(null);
            $table->enum('status', ['new', 'working', 'hired', 'hold'])->default('new');
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
        Schema::dropIfExists('candidates');
    }
};
