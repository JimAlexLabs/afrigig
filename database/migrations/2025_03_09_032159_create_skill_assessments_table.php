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
        Schema::create('skill_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced', 'expert']);
            $table->integer('time_limit')->default(60); // in minutes
            $table->integer('passing_score')->default(70);
            $table->boolean('is_active')->default(true);
            $table->json('questions'); // Array of questions with answers and explanations
            $table->timestamps();
        });

        Schema::create('skill_assessment_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_assessment_id')->constrained()->onDelete('cascade');
            $table->json('answers');
            $table->integer('score');
            $table->boolean('passed');
            $table->integer('time_taken'); // in minutes
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('skill_assessment_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_assessment_attempt_id')->constrained()->onDelete('cascade');
            $table->text('feedback');
            $table->json('improvement_areas');
            $table->json('recommended_resources');
            $table->timestamp('feedback_date')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_assessment_feedback');
        Schema::dropIfExists('skill_assessment_attempts');
        Schema::dropIfExists('skill_assessments');
    }
};
