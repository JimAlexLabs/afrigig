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
        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->enum('proficiency_level', ['beginner', 'intermediate', 'advanced', 'expert']);
            $table->integer('years_experience');
            $table->boolean('is_verified')->default(false);
            $table->string('verification_method')->nullable(); // e.g., 'test', 'certification', 'portfolio'
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_skills');
    }
};
