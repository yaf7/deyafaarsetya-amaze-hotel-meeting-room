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
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('reschedule_status')->nullable()->after('status');
            $table->date('requested_reschedule_date')->nullable()->after('reschedule_status');
            $table->string('requested_reschedule_session')->nullable()->after('requested_reschedule_date');
            $table->text('reschedule_rejection_reason')->nullable()->after('requested_reschedule_session');
            $table->integer('reschedule_count')->default(0)->after('reschedule_rejection_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'reschedule_status',
                'requested_reschedule_date',
                'requested_reschedule_session',
                'reschedule_rejection_reason',
                'reschedule_count',
            ]);
        });
    }
};
