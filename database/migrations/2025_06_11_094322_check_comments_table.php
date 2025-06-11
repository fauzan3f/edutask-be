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
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                // Pastikan kolom-kolom yang diperlukan ada
                if (!Schema::hasColumn('comments', 'content')) {
                    $table->text('content');
                }
                
                if (!Schema::hasColumn('comments', 'user_id')) {
                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
                }
                
                if (!Schema::hasColumn('comments', 'task_id')) {
                    $table->foreignId('task_id')->constrained()->onDelete('cascade');
                }
                
                if (!Schema::hasColumn('comments', 'parent_id')) {
                    $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
                }
                
                if (!Schema::hasColumn('comments', 'is_edited')) {
                    $table->boolean('is_edited')->default(false);
                }
                
                if (!Schema::hasColumn('comments', 'edited_at')) {
                    $table->timestamp('edited_at')->nullable();
                }
            });
        } else {
            // Buat tabel jika belum ada
            Schema::create('comments', function (Blueprint $table) {
                $table->id();
                $table->text('content');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('task_id')->constrained()->onDelete('cascade');
                $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
                $table->boolean('is_edited')->default(false);
                $table->timestamp('edited_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu melakukan apa-apa di sini karena ini hanya migrasi pemeriksaan
    }
};
