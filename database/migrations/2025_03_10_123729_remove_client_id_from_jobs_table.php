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
        Schema::table('jobs', function (Blueprint $table) {
            // Remove client-specific columns
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
            
            // Add new admin-specific columns
            $table->string('location')->after('status');
            $table->enum('job_type', ['full-time', 'part-time', 'contract', 'temporary'])->after('location');
            $table->string('posted_by')->after('job_type')->comment('Name or department that posted the job');
            $table->text('requirements')->after('description')->nullable();
            $table->text('benefits')->nullable()->after('requirements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            // Revert changes
            $table->foreignId('client_id')->after('id')->constrained('users')->onDelete('cascade');
            
            $table->dropColumn([
                'location',
                'job_type',
                'posted_by',
                'requirements',
                'benefits'
            ]);
        });
    }
};
