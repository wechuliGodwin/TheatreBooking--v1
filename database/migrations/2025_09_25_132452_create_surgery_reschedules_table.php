<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurgeryReschedulesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surgery_reschedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surgery_id');
            $table->date('previous_date_of_surgery')->nullable();
            $table->string('previous_surgery')->nullable();
            $table->string('previous_surgeon')->nullable();
            $table->string('previous_surgery_type')->nullable();
            $table->string('previous_surgery_category')->nullable();
            $table->string('previous_sha_procedure')->nullable();
            $table->string('previous_case_order')->nullable();
            $table->string('previous_theatre_room')->nullable();
            $table->string('reason')->nullable();
            $table->unsignedBigInteger('rescheduled_by')->nullable();
            $table->timestamps();

            $table->foreign('surgery_id')->references('id')->on('surgeries')->onDelete('cascade');
            $table->foreign('rescheduled_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surgery_reschedules');
    }
};
