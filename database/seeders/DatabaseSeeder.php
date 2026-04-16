<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    // Koordinat terminal rute Perintis Kampus → Kampus Gowa (koordinat asli Makassar)
    private array $terminalData = [
        [
            'name'        => 'Terminal Perintis Kampus',
            'code'        => 'PERINTIS',
            'lat'         => -5.1326,
            'lng'         => 119.4880,
            'order'       => 1,
            'type'        => 'origin',
            'description' => 'Terminal awal keberangkatan — Jl. Perintis Kemerdekaan, Tamalanrea',
        ],
        [
            'name'        => 'Halte Tamalanrea Indah',
            'code'        => 'TAMAL',
            'lat'         => -5.1456,
            'lng'         => 119.4891,
            'order'       => 2,
            'type'        => 'stop',
            'description' => 'Halte Tamalanrea Indah',
        ],
        [
            'name'        => 'Halte BTP / Antang',
            'code'        => 'ANTANG',
            'lat'         => -5.1612,
            'lng'         => 119.4770,
            'order'       => 3,
            'type'        => 'stop',
            'description' => 'Halte kawasan BTP – Antang',
        ],
        [
            'name'        => 'Halte Pallangga',
            'code'        => 'PALLANGGA',
            'lat'         => -5.1980,
            'lng'         => 119.4590,
            'order'       => 4,
            'type'        => 'stop',
            'description' => 'Halte Pallangga menuju Gowa',
        ],
        [
            'name'        => 'Kampus Non-Merdeka Gowa',
            'code'        => 'GOWA',
            'lat'         => -5.2303,
            'lng'         => 119.4520,
            'order'       => 5,
            'type'        => 'destination',
            'description' => 'Terminal tujuan akhir — Kampus Non-Merdeka Gowa',
        ],
    ];

    public function run(): void
    {
        // 1. Seed Terminals
        $terminals = [];
        foreach ($this->terminalData as $data) {
            $terminals[$data['code']] = Terminal::create($data);
        }

        // 2. Create Admin
        User::create([
            'name'     => 'Admin Transportasi',
            'email'    => 'admin@kampus-non-merdeka.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // 3. Create 13 Sopir
        $drivers = [];
        $driverNames = [
            'Hasan Basri', 'Mukhtar Lede', 'Syamsul Hadi', 'Ridwan Karim',
            'Ambo Dalle', 'Nurdin Sialana', 'Saharuddin', 'Andi Mappa',
            'Baharuddin', 'Suardi Usman', 'Junaidi Rahman', 'Kamaruddin', 'Arifin Dg. Nai'
        ];
        for ($i = 1; $i <= 13; $i++) {
            $drivers[] = User::create([
                'name'     => $driverNames[$i - 1],
                'email'    => 'sopir' . str_pad($i, 2, '0', STR_PAD_LEFT) . '@kampus-non-merdeka.ac.id',
                'password' => Hash::make('password'),
                'role'     => 'sopir',
            ]);
        }

        // 4. Create Users (Civitas & Umum)
        $users = [];
        $users[] = User::create(['name' => 'Muh. Budi Santoso', 'email' => 'budi@kampus-non-merdeka.ac.id', 'password' => Hash::make('password'), 'role' => 'civitas']);
        $users[] = User::create(['name' => 'Ani Wulandari', 'email' => 'ani@gmail.com', 'password' => Hash::make('password'), 'role' => 'umum']);
        $users[] = User::create(['name' => 'Dr. Surya Darma', 'email' => 'surya@kampus-non-merdeka.ac.id', 'password' => Hash::make('password'), 'role' => 'civitas']);
        $users[] = User::create(['name' => 'Andi Fatimah', 'email' => 'andi@yahoo.com', 'password' => Hash::make('password'), 'role' => 'umum']);
        $users[] = User::create(['name' => 'Riska Amalia', 'email' => 'riska@kampus-non-merdeka.ac.id', 'password' => Hash::make('password'), 'role' => 'civitas']);

        // 5. Create 13 Buses
        // Status tersebar realistis: beberapa standby, beberapa jalan, beberapa istirahat
        $tripStatuses = [
            'standby', 'jalan', 'jalan', 'standby', 'jalan',
            'istirahat', 'standby', 'jalan', 'istirahat', 'standby',
            'jalan', 'standby', 'istirahat'
        ];

        // Posisi simulasi: bus yang jalan berada di antara terminal, yang standby di terminal awal, yang istirahat di terminal akhir
        $positions = [
            // standby  → di Perintis
            1  => ['lat' => -5.1326, 'lng' => 119.4880, 'terminal' => 'PERINTIS'],
            // jalan    → antara Perintis & Tamalanrea
            2  => ['lat' => -5.1380, 'lng' => 119.4885, 'terminal' => null],
            // jalan    → antara Tamalanrea & Antang
            3  => ['lat' => -5.1530, 'lng' => 119.4830, 'terminal' => null],
            // standby  → di Perintis
            4  => ['lat' => -5.1326, 'lng' => 119.4880, 'terminal' => 'PERINTIS'],
            // jalan    → antara Antang & Pallangga
            5  => ['lat' => -5.1780, 'lng' => 119.4680, 'terminal' => null],
            // istirahat → di Gowa
            6  => ['lat' => -5.2303, 'lng' => 119.4520, 'terminal' => 'GOWA'],
            // standby  → di Perintis
            7  => ['lat' => -5.1326, 'lng' => 119.4880, 'terminal' => 'PERINTIS'],
            // jalan    → antara Pallangga & Gowa
            8  => ['lat' => -5.2100, 'lng' => 119.4550, 'terminal' => null],
            // istirahat → di Gowa
            9  => ['lat' => -5.2303, 'lng' => 119.4520, 'terminal' => 'GOWA'],
            // standby  → di Perintis
            10 => ['lat' => -5.1326, 'lng' => 119.4880, 'terminal' => 'PERINTIS'],
            // jalan    → antara Tamalanrea & Antang
            11 => ['lat' => -5.1550, 'lng' => 119.4820, 'terminal' => null],
            // standby  → di Perintis
            12 => ['lat' => -5.1326, 'lng' => 119.4880, 'terminal' => 'PERINTIS'],
            // istirahat → di Gowa
            13 => ['lat' => -5.2303, 'lng' => 119.4520, 'terminal' => 'GOWA'],
        ];

        $buses = [];
        for ($i = 1; $i <= 13; $i++) {
            $tripStatus = $tripStatuses[$i - 1];
            $pos = $positions[$i];

            $buses[] = Bus::create([
                'name'             => 'Bus Kampus Non-Merdeka ' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'bus_number'       => $i,
                'driver_id'        => $drivers[$i - 1]->id,
                'plate_number'     => 'DD ' . (1000 + $i) . ' BK',
                'capacity'         => 20,
                'route'            => 'Perintis Kampus → Kampus Gowa',
                'departure_time'   => '05:00',
                'arrival_time'     => '21:00',
                'description'      => 'Armada Bus Kampus Non-Merdeka No. ' . str_pad($i, 2, '0', STR_PAD_LEFT) . '. Rute reguler Perintis–Gowa. Ber-AC, kapasitas 20 penumpang.',
                'status'           => 'active',
                'trip_status'      => $tripStatus,
                'current_lat'      => $pos['lat'],
                'current_lng'      => $pos['lng'],
                'current_terminal' => $pos['terminal'],
                'departed_at'      => $tripStatus === 'jalan' ? now()->subMinutes(rand(5, 25)) : null,
            ]);
        }

        // 6. Buat booking dummy (hanya untuk bus yang standby/jalan, kursi terisi, hari ini)
        $today = today()->toDateString();

        // Bus 01 (standby) — beberapa kursi terisi
        $seatBookings01 = [3, 5, 7, 12, 15];
        foreach ($seatBookings01 as $seat) {
            Booking::create([
                'user_id'        => $users[array_rand($users)]->id,
                'bus_id'         => $buses[0]->id,
                'booking_date'   => $today,
                'seat_number'    => $seat,
                'status'         => 'confirmed',
                'payment_method' => 'etoll',
                'payment_status' => 'paid',
                'etoll_number'   => '1234' . rand(100000000000, 999999999999),
                'is_completed'   => false,
            ]);
        }

        // Bus 04 (standby) — beberapa kursi terisi
        $seatBookings04 = [1, 2, 8, 16];
        foreach ($seatBookings04 as $seat) {
            Booking::create([
                'user_id'        => $users[array_rand($users)]->id,
                'bus_id'         => $buses[3]->id,
                'booking_date'   => $today,
                'seat_number'    => $seat,
                'status'         => 'confirmed',
                'payment_method' => 'qris',
                'payment_status' => 'paid',
                'is_completed'   => false,
            ]);
        }

        // Bus 02 (jalan) — beberapa kursi terisi
        $seatBookings02 = [4, 9, 11, 17, 18];
        foreach ($seatBookings02 as $seat) {
            Booking::create([
                'user_id'        => $users[array_rand($users)]->id,
                'bus_id'         => $buses[1]->id,
                'booking_date'   => $today,
                'seat_number'    => $seat,
                'status'         => 'confirmed',
                'payment_method' => 'qris',
                'payment_status' => 'paid',
                'is_completed'   => false,
            ]);
        }

        // Dummy Tips
        \App\Models\Tip::create(['bus_id' => $buses[0]->id, 'amount' => 5000, 'created_at' => now()->subHours(2)]);
        \App\Models\Tip::create(['bus_id' => $buses[0]->id, 'amount' => 10000, 'created_at' => now()->subHour()]);
        \App\Models\Tip::create(['bus_id' => $buses[3]->id, 'amount' => 5000, 'created_at' => now()->subHours(3)]);
    }
}
