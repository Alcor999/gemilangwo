<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Package;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class June2026BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure required packages exist (create if missing, use existing if found)
        $packages = $this->ensurePackages();

        // Define bookings from the June 2026 data images
        $bookings = [
            [
                'name' => 'Erlyn',
                'email' => 'erlyn.june2026@gemilangwo.test',
                'phone' => '081234560001',
                'event_date' => '2026-06-01',
                'event_location' => 'Gedung Serbaguna Kudus',
                'package_key' => 'all_in_2',
                'total_price' => 183333333,
                'guest_count' => 800,
                'payment_scheme' => 'dp_30',
                'dp_percentage' => 30.00,
                'dp_amount' => 55000000,
                'payment_status' => 'dp_paid',
                'status' => 'confirmed',
            ],
            [
                'name' => 'Kak Nadin',
                'email' => 'nadin.june2026@gemilangwo.test',
                'phone' => '081234560002',
                'event_date' => '2026-06-03',
                'event_location' => 'Gedung Wisma Halim Demak',
                'package_key' => 'bagong',
                'total_price' => 10000000,
                'guest_count' => 500,
                'payment_scheme' => 'dp_30',
                'dp_percentage' => 30.00,
                'dp_amount' => 3000000,
                'payment_status' => 'dp_paid',
                'status' => 'confirmed',
            ],
            [
                'name' => 'Kak Nadya',
                'email' => 'nadya.june2026@gemilangwo.test',
                'phone' => '081234560003',
                'event_date' => '2026-06-10',
                'event_location' => 'Gedung Wisma Haji Dempet Demak',
                'package_key' => 'bagong',
                'total_price' => 10000000,
                'guest_count' => 500,
                'payment_scheme' => 'dp_20',
                'dp_percentage' => 20.00,
                'dp_amount' => 2000000,
                'payment_status' => 'dp_paid',
                'status' => 'confirmed',
                'special_request' => 'Paket Bagong',
            ],
            [
                'name' => 'Kak Nadya',
                'email' => 'nadya.june2026@gemilangwo.test',
                'phone' => '081234560003',
                'event_date' => '2026-06-10',
                'event_location' => 'Gedung Wisma Halim DEMAK',
                'package_key' => 'silver_custom',
                'total_price' => 9550000,
                'guest_count' => 400,
                'payment_scheme' => 'dp_20',
                'dp_percentage' => 20.00,
                'dp_amount' => 2250000,
                'payment_status' => 'dp_paid',
                'status' => 'confirmed',
                'special_request' => 'Paket Silver - Add on Dekor tambah tenda, kursi, charge dekor Utama, genset, lightning, standing ac, transport team wo, pranata cara, talent music',
            ],
            [
                'name' => 'Kak Hayyun',
                'email' => 'hayyun.june2026@gemilangwo.test',
                'phone' => '081234560005',
                'event_date' => '2026-06-13',
                'event_location' => 'Sapphire Hotel Lingkar',
                'package_key' => 'deluxe',
                'total_price' => 17000000,
                'guest_count' => 300,
                'payment_scheme' => 'dp_30',
                'dp_percentage' => 30.00,
                'dp_amount' => 5100000,
                'payment_status' => 'dp_paid',
                'status' => 'confirmed',
                'special_request' => 'Custom dari paket deluxe',
            ],
            [
                'name' => 'Kak Futikha',
                'email' => 'futikha.june2026@gemilangwo.test',
                'phone' => '081234560006',
                'event_date' => '2026-06-14',
                'event_location' => 'Sapphire Hotel Lingkar',
                'package_key' => 'all_in_2',
                'total_price' => 164000000,
                'guest_count' => 1000,
                'payment_scheme' => 'installment_5x',
                'dp_percentage' => null,
                'dp_amount' => null,
                'payment_status' => 'partially_paid',
                'status' => 'confirmed',
                'special_request' => 'Add on: Photo booth, lightning, MUA, Domas, Charge Venue, Dekor Utama, Catering, Package Catering',
                'installments' => [
                    ['amount' => 5100000, 'installment_number' => 1, 'status' => 'success'],
                    ['amount' => 5000000, 'installment_number' => 2, 'status' => 'success'],
                    ['amount' => 25000000, 'installment_number' => 3, 'status' => 'pending'],
                ],
            ],
            [
                'name' => 'Kak Navi',
                'email' => 'navi.june2026@gemilangwo.test',
                'phone' => '081234560007',
                'event_date' => '2026-06-14',
                'event_location' => 'Gedung DPRD Kudus',
                'package_key' => 'petruk',
                'total_price' => 9500000,
                'guest_count' => 400,
                'payment_scheme' => 'dp_20',
                'dp_percentage' => 20.00,
                'dp_amount' => 2250000,
                'payment_status' => 'dp_paid',
                'status' => 'confirmed',
                'special_request' => 'Charge tamu, add on kipas blower',
            ],
            [
                'name' => 'Kak Arina',
                'email' => 'arina.june2026@gemilangwo.test',
                'phone' => '081234560008',
                'event_date' => '2026-06-20',
                'event_location' => 'Gedung Mitua Vie Tahunan Jepara',
                'package_key' => 'bagong',
                'total_price' => 9500000,
                'guest_count' => 500,
                'payment_scheme' => 'dp_20',
                'dp_percentage' => 20.00,
                'dp_amount' => 1500000,
                'payment_status' => 'dp_paid',
                'status' => 'confirmed',
                'special_request' => 'Paket Bagong Resepsi Only',
            ],
            [
                'name' => 'Kak Risma',
                'email' => 'risma.june2026@gemilangwo.test',
                'phone' => '081234560009',
                'event_date' => '2026-06-21',
                'event_location' => 'Gedung Wisma Halim Demak',
                'package_key' => 'bagong',
                'total_price' => 10000000,
                'guest_count' => 500,
                'payment_scheme' => 'dp_30',
                'dp_percentage' => 30.00,
                'dp_amount' => 3000000,
                'payment_status' => 'dp_paid',
                'status' => 'confirmed',
                'special_request' => 'Paket Bagong Resepsi Only',
            ],
            [
                'name' => 'Kak Navi',
                'email' => 'navi.june2026@gemilangwo.test',
                'phone' => '081234560007',
                'event_date' => '2026-06-27',
                'event_location' => 'Sekuro Village Venue',
                'package_key' => 'petruk',
                'total_price' => 9500000,
                'guest_count' => 300,
                'payment_scheme' => 'dp_30',
                'dp_percentage' => 30.00,
                'dp_amount' => 2850000,
                'payment_status' => 'dp_paid',
                'status' => 'confirmed',
            ],
        ];

        foreach ($bookings as $data) {
            // Create customer user
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                    'phone' => $data['phone'],
                    'role' => 'customer',
                    'email_verified_at' => now(),
                ]
            );

            $package = $packages[$data['package_key']];

            // Create order (deterministic order_number based on date + package for idempotency)
            $orderNumber = 'WO-' . str_replace('-', '', $data['event_date']) . '-' . strtoupper($data['package_key']);

            $totalPaid = 0;
            if (isset($data['installments'])) {
                foreach ($data['installments'] as $inst) {
                    if ($inst['status'] === 'success') {
                        $totalPaid += $inst['amount'];
                    }
                }
            } elseif ($data['dp_amount'] && $data['payment_status'] !== 'unpaid') {
                $totalPaid = $data['dp_amount'];
            }

            $order = Order::firstOrCreate(
                ['order_number' => $orderNumber],
                [
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'event_date' => $data['event_date'],
                    'event_location' => $data['event_location'],
                    'guest_count' => $data['guest_count'],
                    'special_request' => $data['special_request'] ?? null,
                    'total_price' => $data['total_price'],
                    'payment_scheme' => $data['payment_scheme'],
                    'dp_percentage' => $data['dp_percentage'],
                    'total_paid' => $totalPaid,
                    'remaining_amount' => $data['total_price'] - $totalPaid,
                    'payment_status' => $data['payment_status'],
                    'status' => $data['status'],
                ]
            );

            // Ensure calendar event is created for confirmed bookings
            if (in_array($order->status, ['confirmed', 'completed', 'in_progress']) && !$order->calendarEvent) {
                \App\Models\CalendarEvent::createFromOrder($order);
            }

            // Create payment records
            if (isset($data['installments'])) {
                foreach ($data['installments'] as $inst) {
                    Payment::firstOrCreate(
                        [
                            'order_id' => $order->id,
                            'installment_number' => $inst['installment_number'],
                            'payment_type' => 'installment',
                        ],
                        [
                            'payment_id' => 'PAY-INST-' . strtoupper(uniqid()) . '-' . rand(100, 999),
                            'payment_method' => 'bank_transfer',
                            'amount' => $inst['amount'],
                            'status' => $inst['status'],
                            'verification_status' => $inst['status'] === 'success' ? 'verified' : 'pending',
                            'paid_at' => $inst['status'] === 'success' ? Carbon::parse($data['event_date'])->subMonths(2) : null,
                        ]
                    );
                }
            } elseif ($data['dp_amount'] && $data['payment_status'] !== 'unpaid') {
                Payment::firstOrCreate(
                    [
                        'order_id' => $order->id,
                        'payment_type' => 'dp',
                    ],
                    [
                        'payment_id' => 'PAY-DP-' . strtoupper(uniqid()) . '-' . rand(100, 999),
                        'payment_method' => 'bank_transfer',
                        'amount' => $data['dp_amount'],
                        'status' => 'success',
                        'verification_status' => 'verified',
                        'paid_at' => Carbon::parse($data['event_date'])->subMonths(1),
                    ]
                );
            }
        }

        $this->command->info('June 2026 bookings seeded successfully! (' . count($bookings) . ' bookings created)');
    }

    private function ensurePackages(): array
    {
        $map = [];

        // Map existing packages
        $map['bagong'] = Package::firstOrCreate(
            ['name' => 'Bagong'],
            ['price' => 10000000, 'status' => 'active', 'max_guests' => 500, 'features' => json_encode(['Dekorasi lengkap', 'Catering 500 porsi', 'Dokumentasi foto & video', 'MC profesional', 'Musik entertainment'])]
        );

        $map['petruk'] = Package::firstOrCreate(
            ['name' => 'Petruk'],
            ['price' => 7500000, 'status' => 'active', 'max_guests' => 400, 'features' => json_encode(['Dekorasi standar', 'Catering 400 porsi', 'Dokumentasi foto', 'MC profesional'])]
        );

        $map['deluxe'] = Package::firstOrCreate(
            ['name' => 'Deluxe'],
            ['price' => 17000000, 'status' => 'active', 'max_guests' => 300, 'features' => json_encode(['Dekorasi premium', 'Catering 300 porsi', 'Dokumentasi lengkap', 'MC & musik live', 'Wedding planner'])]
        );

        $map['silver_custom'] = Package::firstOrCreate(
            ['name' => 'Silver'],
            ['price' => 57000000, 'status' => 'active', 'max_guests' => 500, 'features' => json_encode(['Dekorasi mewah', 'Catering premium', 'Dokumentasi cinematic', 'MC profesional', 'Live band'])]
        );

        $map['all_in_2'] = Package::firstOrCreate(
            ['name' => 'All in 2'],
            ['price' => 170000000, 'status' => 'active', 'max_guests' => 1000, 'features' => json_encode(['Full dekorasi luxury', 'Catering 1000 porsi', 'Dokumentasi cinematic 4K', 'MC artis', 'Live orchestra', 'Wedding planner premium', 'Photo booth', 'Lighting profesional'])]
        );

        return $map;
    }
}
