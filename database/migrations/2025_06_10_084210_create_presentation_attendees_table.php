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
        Schema::create('presentation_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presentation_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_attending')->nullable(); // true, false, or null (not responded)
            $table->text('response_notes')->nullable();
            $table->timestamp('notification_sent_at')->nullable();
            $table->timestamps();

            // Prevent duplicate entries
            $table->unique(['presentation_schedule_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presentation_attendees');
    }
};
