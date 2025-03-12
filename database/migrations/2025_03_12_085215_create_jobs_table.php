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
            $table->string('title');
            $table->text('description');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('freelancer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->decimal('budget_min', 10, 2);
            $table->decimal('budget_max', 10, 2);
            $table->timestamp('deadline');
            $table->json('required_skills');
            $table->enum('experience_level', ['entry', 'intermediate', 'expert'])->default('entry');
            $table->enum('project_length', ['short', 'medium', 'long'])->default('medium');
            $table->enum('project_type', ['fixed', 'hourly'])->default('fixed');
            $table->enum('status', ['open', 'in_progress', 'completed', 'cancelled'])->default('open');
            $table->json('attachments')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_urgent')->default(false);
            $table->timestamps();
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
