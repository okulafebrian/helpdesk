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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('number', 32)->unique();
            $table->string('title');
            $table->text('description');
            $table->string('status');
            $table->string('priority');
            $table->string('category');
            $table->foreignId('location_id')->constrained('locations');
            $table->foreignId('requester_id')->constrained('users');
            $table->foreignId('assignee_id')->nullable()->constrained('users');
            $table->timestamp('first_response_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('response_due_at');
            $table->timestamp('resolution_due_at');
            $table->string('response_sla_status');
            $table->string('resolution_sla_status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
