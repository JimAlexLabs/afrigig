<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced']);
            $table->integer('duration_minutes');
            $table->integer('passing_score');
            $table->json('questions');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pivot table for user test attempts
        Schema::create('test_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('score');
            $table->timestamp('completed_at');
            $table->boolean('passed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_user');
        Schema::dropIfExists('tests');
    }
}; 