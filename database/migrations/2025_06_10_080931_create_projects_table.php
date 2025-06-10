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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('manager_id')->constrained('users');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['planning', 'in_progress', 'on_hold', 'completed', 'cancelled'])->default('planning');
            $table->string('priority', 20)->default('medium');
            $table->string('code', 20)->unique();
            $table->boolean('is_archived')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
