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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['freelancer', 'client', 'admin'])->default('freelancer');
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->json('skills')->nullable();
            $table->decimal('balance', 10, 2)->default(0);
            $table->decimal('rating', 3, 1)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('google_id')->nullable();
            $table->string('linkedin_id')->nullable();
            $table->boolean('terms_accepted')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
