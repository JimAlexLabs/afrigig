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
            $table->string('role')->default('freelancer');
            $table->decimal('balance', 10, 2)->default(0);
            $table->boolean('email_notifications')->default(true);
            $table->boolean('push_notifications')->default(true);
            $table->boolean('bid_updates')->default(true);
            $table->boolean('job_alerts')->default(true);
            $table->boolean('message_notifications')->default(true);
            $table->boolean('two_factor_enabled')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'balance',
                'email_notifications',
                'push_notifications',
                'bid_updates',
                'job_alerts',
                'message_notifications',
                'two_factor_enabled',
            ]);
        });
    }
}; 