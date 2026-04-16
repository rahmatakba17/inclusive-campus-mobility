<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * DisabilityUserSeeder
 *
 * Menyediakan akun pengujian untuk skenario inklusif Bus Kampus Non-Merdeka.
 * Mencakup seluruh spektrum prioritas:
 *   - medium : Lansia / Ibu Hamil  → Tarif normal, kursi zona depan
 *   - high   : Pengguna Kursi Roda → GRATIS (subsidi penuh Kampus Non-Merdeka)
 *   - other  : Kondisi Medis Khusus → Tarif normal, kursi zona depan
 *
 * Password semua akun: password
 *
 * Jalankan: php artisan db:seed --class=DisabilityUserSeeder
 */
class DisabilityUserSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [

            // ══════════════════════════════════════════════════════
            //  SIVITAS AKADEMIKA — Disabilitas (isCivitas = true)
            //  Tarif normal Rp 3.000 | Kecuali high → GRATIS
            //  Boleh E-Tol & QRIS | Maks 4 kursi reguler
            // ══════════════════════════════════════════════════════

            [
                // Prioritas TINGGI: Mahasiswa pengguna kursi roda → GRATIS
                'name'  => 'Ahmad Fauzan (Kursi Roda)',
                'email' => 'ahmad.difabel@kampus-non-merdeka.ac.id',
                'role'  => 'civitas',
                'note'  => 'Prioritas Tinggi (high) — Kursi Roda — GRATIS',
            ],
            [
                // Prioritas SEDANG: Dosen lansia
                'name'  => 'Prof. Darwis Lanjut Usia',
                'email' => 'darwis.lansia@kampus-non-merdeka.ac.id',
                'role'  => 'civitas',
                'note'  => 'Prioritas Ringan/Sedang (medium) — Lansia — Rp3.000',
            ],
            [
                // Prioritas LAINNYA: Kondisi medis (pasca operasi)
                'name'  => 'Siti Rahma Medis Khusus',
                'email' => 'siti.medis@kampus-non-merdeka.ac.id',
                'role'  => 'civitas',
                'note'  => 'Prioritas Lainnya (other) — Kondisi Medis — Rp3.000',
            ],
            [
                // Prioritas SEDANG: Mahasiswi hamil
                'name'  => 'Nurul Aisyah (Hamil)',
                'email' => 'nurul.hamil@kampus-non-merdeka.ac.id',
                'role'  => 'civitas',
                'note'  => 'Prioritas Ringan/Sedang (medium) — Ibu Hamil — Rp3.000',
            ],

            // ══════════════════════════════════════════════════════
            //  PENGGUNA UMUM — Disabilitas (isUmum = true)
            //  Tarif normal Rp 5.000 | Kecuali high → GRATIS
            //  Hanya QRIS | Maks 1 kursi per transaksi
            // ══════════════════════════════════════════════════════

            [
                // Prioritas TINGGI: Pengunjung kursi roda → GRATIS
                'name'  => 'Baharuddin (Kursi Roda)',
                'email' => 'bahar.difabel@gmail.com',
                'role'  => 'umum',
                'note'  => 'Prioritas Tinggi (high) — Kursi Roda — GRATIS',
            ],
            [
                // Prioritas SEDANG: Lansia umum
                'name'  => 'Hj. Ramlah Lanjut Usia',
                'email' => 'ramlah.lansia@gmail.com',
                'role'  => 'umum',
                'note'  => 'Prioritas Ringan/Sedang (medium) — Lansia — Rp5.000',
            ],
            [
                // Prioritas LAINNYA: Kondisi medis (tamu dengan kebutuhan khusus)
                'name'  => 'Irwan Syah Medis',
                'email' => 'irwan.medis@yahoo.com',
                'role'  => 'umum',
                'note'  => 'Prioritas Lainnya (other) — Kondisi Medis — Rp5.000',
            ],
            [
                // Prioritas SEDANG: Tamu hamil
                'name'  => 'Fatimah Az-Zahra (Hamil)',
                'email' => 'fatimah.hamil@gmail.com',
                'role'  => 'umum',
                'note'  => 'Prioritas Ringan/Sedang (medium) — Ibu Hamil — Rp5.000',
            ],
        ];

        $created = 0;
        $skipped = 0;

        foreach ($accounts as $account) {
            if (User::where('email', $account['email'])->exists()) {
                $this->command->warn("  SKIP  → {$account['email']} (sudah ada)");
                $skipped++;
                continue;
            }

            User::create([
                'name'     => $account['name'],
                'email'    => $account['email'],
                'password' => Hash::make('password'),
                'role'     => $account['role'],
            ]);

            $roleLabel = $account['role'] === 'civitas' ? 'Sivitas' : 'Umum  ';
            $this->command->info("  OK    → [{$roleLabel}] {$account['name']} ({$account['note']})");
            $created++;
        }

        $this->command->newLine();
        $this->command->info("  Selesai: {$created} akun dibuat, {$skipped} dilewati.");
        $this->command->newLine();
        $this->command->line('  Password semua akun: <comment>password</comment>');
        $this->command->newLine();
        $this->command->line('  ┌─ SIVITAS (E-Tol/QRIS, maks 4 kursi) ──────────────────────────────────┐');
        $this->command->line('  │  ahmad.difabel@kampus-non-merdeka.ac.id  → Kursi Roda     → Prioritas TINGGI (GRATIS) │');
        $this->command->line('  │  darwis.lansia@kampus-non-merdeka.ac.id  → Lansia         → Prioritas SEDANG (Rp3000) │');
        $this->command->line('  │  siti.medis@kampus-non-merdeka.ac.id     → Medis Khusus   → Prioritas LAIN  (Rp3000) │');
        $this->command->line('  │  nurul.hamil@kampus-non-merdeka.ac.id    → Ibu Hamil      → Prioritas SEDANG (Rp3000) │');
        $this->command->line('  └──────────────────────────────────────────────────────────────────────────┘');
        $this->command->line('  ┌─ UMUM (QRIS only, maks 1 kursi) ──────────────────────────────────────┐');
        $this->command->line('  │  bahar.difabel@gmail.com    → Kursi Roda     → Prioritas TINGGI (GRATIS) │');
        $this->command->line('  │  ramlah.lansia@gmail.com    → Lansia         → Prioritas SEDANG (Rp5000) │');
        $this->command->line('  │  irwan.medis@yahoo.com      → Medis Khusus   → Prioritas LAIN  (Rp5000) │');
        $this->command->line('  │  fatimah.hamil@gmail.com    → Ibu Hamil      → Prioritas SEDANG (Rp5000) │');
        $this->command->line('  └──────────────────────────────────────────────────────────────────────────┘');
    }
}
