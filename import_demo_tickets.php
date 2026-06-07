<?php
// import_demo_tickets.php
require_once __DIR__ . '/core/db_connect.php';
require_once __DIR__ . '/core/safegate_repository.php';

$userId = sg_current_user_id();

if (!$userId) {
    echo "<div style='font-family: sans-serif; text-align: center; margin-top: 100px;'>";
    echo "<h2>Silakan login terlebih dahulu ke website Anda.</h2>";
    echo "<p>Kunjungi <a href='index.php?page=login'>Halaman Login</a>, setelah masuk kembali ke halaman ini untuk membuat tiket dummy.</p>";
    echo "</div>";
    exit;
}

// 1. Pastikan demo events terisi secara aman
$demoEventsData = [
    [
        'title' => 'The Eras Tour - London',
        'venue' => 'Wembley Stadium',
        'city' => 'London, UK',
        'date' => '2026-08-17 19:00:00',
        'time' => '19:00:00',
        'desc' => 'Join Taylor Swift for a legendary journey through her musical eras.'
    ],
    [
        'title' => 'Coldplay Music of the Spheres',
        'venue' => 'GBK Stadium',
        'city' => 'Jakarta',
        'date' => '2026-09-08 20:00:00',
        'time' => '20:00:00',
        'desc' => 'Coldplay live concert with verified SafeGate access.'
    ],
    [
        'title' => 'Premier League: London Derby',
        'venue' => 'London Stadium',
        'city' => 'London, UK',
        'date' => '2026-10-12 18:30:00',
        'time' => '18:30:00',
        'desc' => 'Premium football seat with official listing verification.'
    ]
];

$eventIds = [];
foreach ($demoEventsData as $ed) {
    $existing = sg_fetch_one('SELECT id FROM events WHERE title = :title LIMIT 1', ['title' => $ed['title']]);
    if ($existing) {
        $eventIds[] = $existing['id'];
    } else {
        $created = sg_execute(
            'INSERT INTO events (title, venue, city, event_date, event_time, description)
             VALUES (:title, :venue, :city, :event_date, :event_time, :description)',
            [
                'title' => $ed['title'],
                'venue' => $ed['venue'],
                'city' => $ed['city'],
                'event_date' => $ed['date'],
                'event_time' => $ed['time'],
                'description' => $ed['desc']
            ]
        );
        if ($created) {
            $db = sg_db();
            $eventIds[] = $db->lastInsertId();
        }
    }
}

if (count($eventIds) < 3) {
    echo "Gagal memproses data event demo.";
    exit;
}

// Ambil salah satu seller yang valid di database untuk menghindari error foreign key
$sellerRow = sg_fetch_one("SELECT id FROM users WHERE role = 'seller' LIMIT 1");
$sellerId = $sellerRow ? (int) $sellerRow['id'] : $userId;

// Buat beberapa tiket dummy
$ticketsData = [
    [
        'event_id' => $eventIds[0],
        'title' => $demoEventsData[0]['title'],
        'section' => 'VVIP-1',
        'row' => 'A',
        'seat' => '12',
        'price' => 2500000,
        'proof' => 'assets/uploads/tickets/sample_eras.pdf'
    ],
    [
        'event_id' => $eventIds[1],
        'title' => $demoEventsData[1]['title'],
        'section' => 'CAT-2',
        'row' => 'F',
        'seat' => '24',
        'price' => 1500000,
        'proof' => 'assets/uploads/tickets/sample_coldplay.pdf'
    ],
    [
        'event_id' => $eventIds[2],
        'title' => $demoEventsData[2]['title'],
        'section' => 'WEST-B',
        'row' => 'K',
        'seat' => '07',
        'price' => 850000,
        'proof' => 'assets/uploads/tickets/sample_pl.pdf'
    ]
];

$insertedCount = 0;

foreach ($ticketsData as $tk) {
    // Cek apakah transaksi serupa sudah pernah dimasukkan agar tidak duplikat berkali-kali jika halaman di-refresh
    $duplicateCheck = sg_fetch_one(
        'SELECT t.id FROM transactions t 
         JOIN ticket_listings tl ON tl.id = t.listing_id 
         WHERE t.buyer_id = :buyer_id AND tl.event_id = :event_id AND tl.section = :section AND tl.row = :row AND tl.seat = :seat LIMIT 1',
        [
            'buyer_id' => $userId,
            'event_id' => $tk['event_id'],
            'section' => $tk['section'],
            'row' => $tk['row'],
            'seat' => $tk['seat']
        ]
    );

    if ($duplicateCheck) {
        continue;
    }

    // 1. Buat ticket listing
    $listingCreated = sg_execute(
        'INSERT INTO ticket_listings 
            (seller_id, event_id, section, `row`, seat, face_value, starting_bid, reserve_price, current_highest_bid, listing_status, ticket_proof_path)
         VALUES 
            (:seller_id, :event_id, :section, :row, :seat, :price, :price, :price, :price, "sold", :proof)',
        [
            'seller_id' => $sellerId,
            'event_id' => $tk['event_id'],
            'section' => $tk['section'],
            'row' => $tk['row'],
            'seat' => $tk['seat'],
            'price' => $tk['price'],
            'proof' => $tk['proof']
        ]
    );

    if ($listingCreated) {
        $db = sg_db();
        $listingId = $db->lastInsertId();

        // 2. Buat transaksi sukses (paid & escrow holding)
        $transactionCode = 'SG-TX-' . strtoupper(substr(uniqid(), -6));
        $txCreated = sg_execute(
            'INSERT INTO transactions
                (transaction_code, listing_id, buyer_id, seller_id, base_price, service_fee, escrow_insurance, total_amount, platform_revenue, seller_earning, payment_method, payment_status, escrow_status, buyer_ticket_status, paid_at)
             VALUES
                (:code, :listing_id, :buyer_id, :seller_id, :price, 0, 0, :price, 0, :price, "bank_transfer", "paid", "holding", "pending_use", NOW())',
            [
                'code' => $transactionCode,
                'listing_id' => $listingId,
                'buyer_id' => $userId,
                'seller_id' => $sellerId,
                'price' => $tk['price']
            ]
        );

        if ($txCreated) {
            $insertedCount++;
        }
    }
}

echo "<div style='font-family: sans-serif; text-align: center; margin-top: 100px;'>";
if ($insertedCount > 0) {
    echo "<h2 style='color: #2ecc71;'>Sukses!</h2>";
    echo "<p>Berhasil membuat $insertedCount tiket dummy untuk akun Anda (User ID: $userId).</p>";
} else {
    echo "<h2 style='color: #3498db;'>Tiket Sudah Ada</h2>";
    echo "<p>Akun Anda sudah memiliki tiket demo ini sebelumnya.</p>";
}
echo "<p>Silakan buka <a href='index.php?page=my_tickets'>Menu Tiket Saya (My Tickets)</a> untuk melihat hasilnya.</p>";
echo "</div>";
