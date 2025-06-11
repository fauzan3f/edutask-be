<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah tipe data kolom deadline di tabel projects
        Schema::table('projects', function (Blueprint $table) {
            // Pastikan kolom deadline memiliki tipe date (tidak termasuk time)
            $table->date('deadline')->change();
        });
        
        // Perbaiki format deadline yang ada
        $projects = DB::table('projects')->get();
        
        foreach ($projects as $project) {
            if ($project->deadline) {
                // Ambil hanya bagian tanggal dari timestamp
                $dateOnly = date('Y-m-d', strtotime($project->deadline));
                
                DB::table('projects')
                    ->where('id', $project->id)
                    ->update(['deadline' => $dateOnly]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Kembalikan ke format timestamp jika diperlukan
            $table->timestamp('deadline')->nullable()->change();
        });
    }
};
