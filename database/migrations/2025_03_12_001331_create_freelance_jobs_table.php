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
        Schema::create('freelance_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('freelancer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('category');
            $table->text('skills_required');
            $table->decimal('budget_min', 10, 2);
            $table->decimal('budget_max', 10, 2);
            $table->dateTime('deadline');
            $table->string('status')->default('open');
            $table->string('experience_level');
            $table->string('project_length');
            $table->text('attachments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelance_jobs');
    }
};
