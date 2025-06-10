<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeamMember;
use Illuminate\Support\Facades\DB;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada
        DB::table('team_members')->truncate();

        // Data anggota kelompok
        $members = [
            [
                'name' => 'Muhammad Fauzan',
                'nim' => '152023081',
                'contribution' => 'Membuat aplikasi ini (pengembangan utama)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rizki Yudistira',
                'nim' => '152023084',
                'contribution' => 'Memperbaiki frontend',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Irsa Nurrohim',
                'nim' => '152023205',
                'contribution' => 'Memperbaiki backend',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Masukkan data ke database
        foreach ($members as $member) {
            DB::table('team_members')->insert($member);
        }
    }
} 