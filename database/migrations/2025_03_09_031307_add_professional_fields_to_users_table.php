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
        Schema::table('users', function (Blueprint $table) {
            // Professional Information
            $table->integer('experience_years')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->string('education')->nullable();
            $table->json('certifications')->nullable();
            $table->json('social_links')->nullable();
            $table->json('languages')->nullable();
            
            // Location Information
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            
            // Verification and Status
            $table->boolean('registration_fee_paid')->default(false);
            $table->boolean('payment_verified')->default(false);
            $table->enum('availability_status', ['available', 'busy', 'unavailable'])->default('available');
            $table->integer('completed_jobs_count')->default(0);
            $table->decimal('success_rate', 5, 2)->default(0.00);
            
            // Registration Fee Information
            $table->decimal('registration_fee_amount', 10, 2)->nullable();
            $table->timestamp('registration_fee_paid_at')->nullable();
            $table->string('registration_payment_method')->nullable();
            $table->string('registration_transaction_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'experience_years',
                'hourly_rate',
                'education',
                'certifications',
                'social_links',
                'languages',
                'address',
                'city',
                'registration_fee_paid',
                'payment_verified',
                'availability_status',
                'completed_jobs_count',
                'success_rate',
                'registration_fee_amount',
                'registration_fee_paid_at',
                'registration_payment_method',
                'registration_transaction_id'
            ]);
        });
    }
};
