<?php
// core/safegate_repository.php

require_once __DIR__ . '/db_connect.php';

function sg_method_label(string $method): string
{
    $labels = [
        'bank_transfer' => 'Bank Transfer',
        'dana' => 'DANA',
        'gopay' => 'GoPay',
        'ovo' => 'OVO',
        'usdc' => 'USDC Wallet',
    ];

    return $labels[$method] ?? ucwords(str_replace('_', ' ', $method));
}

function sg_listing_status_label(string $status): string
{
    $labels = [
        'pending_review' => 'Under Review',
        'active' => 'Auction Live',
        'paused' => 'Paused',
        'sold' => 'Sold',
        'cancelled' => 'Cancelled',
        'promoted' => 'Promoted',
    ];

    return $labels[$status] ?? ucwords(str_replace('_', ' ', $status));
}

function sg_payment_status_class(string $paymentStatus, string $escrowStatus = ''): string
{
    if ($paymentStatus === 'failed' || $paymentStatus === 'refunded' || $escrowStatus === 'refunded') {
        return 'cancelled';
    }

    if ($paymentStatus === 'pending' || $escrowStatus === 'holding' || $escrowStatus === 'disputed') {
        return 'pending';
    }

    return 'completed';
}

function sg_transaction_status_label(string $paymentStatus, string $escrowStatus): string
{
    if ($paymentStatus === 'failed' || $paymentStatus === 'refunded' || $escrowStatus === 'refunded') {
        return 'CANCELLED';
    }

    if ($paymentStatus === 'pending' || $escrowStatus === 'holding' || $escrowStatus === 'disputed') {
        return 'PENDING';
    }

    return 'COMPLETED';
}

function sg_transaction_note(string $paymentStatus, string $escrowStatus): string
{
    if ($escrowStatus === 'released') {
        return 'VERIFIED DIRECT';
    }

    if ($escrowStatus === 'holding') {
        return 'ESCROW LOCKED';
    }

    if ($escrowStatus === 'refunded') {
        return 'REFUND INITIATED';
    }

    if ($paymentStatus === 'paid') {
        return 'INCL. PROCESSING';
    }

    return strtoupper(str_replace('_', ' ', $paymentStatus));
}

function sg_time_ago(?string $date): string
{
    if (!$date) {
        return '-';
    }

    $timestamp = strtotime($date);
    if (!$timestamp) {
        return '-';
    }

    $diff = max(0, time() - $timestamp);
    if ($diff < 60) {
        return 'just now';
    }
    if ($diff < 3600) {
        return floor($diff / 60) . ' mins ago';
    }
    if ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    }

    return floor($diff / 86400) . ' days ago';
}

function sg_event_image(?string $title, ?string $imagePath = null, ?string $description = null): string
{
    $imagePath = trim((string) $imagePath);
    if ($imagePath !== '') {
        return $imagePath;
    }

    $text = strtolower(trim((string) $title . ' ' . (string) $description));
    $images = [
        'coldplay' => 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&q=80&w=900',
        'nba' => 'https://images.unsplash.com/photo-1504450758481-7338eba7524a?auto=format&fit=crop&q=80&w=900',
        'basket' => 'https://images.unsplash.com/photo-1504450758481-7338eba7524a?auto=format&fit=crop&q=80&w=900',
        'league' => 'https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?auto=format&fit=crop&q=80&w=900',
        'derby' => 'https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?auto=format&fit=crop&q=80&w=900',
        'football' => 'https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?auto=format&fit=crop&q=80&w=900',
        'jazz' => 'https://images.unsplash.com/photo-1511192336575-5a79af67a629?auto=format&fit=crop&q=80&w=900',
        'tomorrowland' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&q=80&w=900',
        'festival' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&q=80&w=900',
        'opera' => 'https://images.unsplash.com/photo-1503095396549-807759245b35?auto=format&fit=crop&q=80&w=900',
        'eras' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&q=80&w=900',
        'taylor' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&q=80&w=900',
        'symphony' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&q=80&w=900',
        'music' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&q=80&w=900',
        'konser' => 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&q=80&w=900',
        'concert' => 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&q=80&w=900',
        'tour' => 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&q=80&w=900',
    ];

    foreach ($images as $keyword => $url) {
        if (strpos($text, $keyword) !== false) {
            return $url;
        }
    }

    return 'https://images.unsplash.com/photo-1540039155733-d7696f4ad9b2?auto=format&fit=crop&q=80&w=900';
}

function sg_event_thumb_class(?string $title, ?string $description = null): string
{
    $text = strtolower(trim((string) $title . ' ' . (string) $description));
    $map = [
        'nba' => 'ball',
        'basket' => 'ball',
        'league' => 'stadium',
        'derby' => 'stadium',
        'football' => 'stadium',
        'opera' => 'opera',
        'jazz' => 'opera',
        'coldplay' => 'neon',
        'tomorrowland' => 'neon',
        'festival' => 'neon',
        'eras' => 'neon',
        'taylor' => 'neon',
        'music' => 'neon',
        'konser' => 'neon',
        'concert' => 'neon',
        'tour' => 'neon',
    ];

    foreach ($map as $keyword => $class) {
        if (strpos($text, $keyword) !== false) {
            return $class;
        }
    }

    return 'neon';
}

function sg_notify(int $userId, string $type, string $title, string $body, ?int $relatedId = null): bool
{
    if ($userId <= 0 || !sg_db()) {
        return false;
    }

    return sg_execute(
        'INSERT INTO notifications (user_id, type, title, body, related_id)
         VALUES (:user_id, :type, :title, :body, :related_id)',
        [
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'related_id' => $relatedId,
        ]
    );
}

function sg_notify_admins(string $type, string $title, string $body, ?int $relatedId = null): void
{
    $admins = sg_fetch_all('SELECT id FROM users WHERE role = "admin" AND is_active = 1');
    foreach ($admins as $admin) {
        sg_notify((int) $admin['id'], $type, $title, $body, $relatedId);
    }
}

function sg_get_notifications(?int $userId, int $limit = 6): array
{
    if (!$userId) {
        return [];
    }

    return sg_fetch_all(
        'SELECT id, type, title, body, is_read, related_id, created_at
         FROM notifications
         WHERE user_id = :user_id
         ORDER BY created_at DESC
         LIMIT ' . max(1, min(20, $limit)),
        ['user_id' => $userId]
    );
}

function sg_unread_notification_count(?int $userId): int
{
    if (!$userId) {
        return 0;
    }

    $row = sg_fetch_one('SELECT COUNT(*) AS total FROM notifications WHERE user_id = :user_id AND is_read = 0', [
        'user_id' => $userId,
    ]);

    return (int) ($row['total'] ?? 0);
}

function sg_ensure_demo_user(string $role): ?int
{
    if (!empty($_SESSION['user_id']) && ($_SESSION['role'] ?? null) === $role) {
        return (int) $_SESSION['user_id'];
    }

    $email = $role . '@safegate.local';
    $name = $role === 'admin' ? 'SafeGate Admin' : 'Verified Vendor';

    $existing = sg_fetch_one('SELECT id FROM users WHERE email = :email LIMIT 1', ['email' => $email]);
    if ($existing) {
        return (int) $existing['id'];
    }

    if (!sg_db()) {
        return null;
    }

    sg_execute(
        'INSERT INTO users (full_name, email, phone_number, password_hash, role)
         VALUES (:name, :email, :phone, :password, :role)',
        [
            'name' => $name,
            'email' => $email,
            'phone' => '+6281234567890',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'role' => $role,
        ]
    );

    $user = sg_fetch_one('SELECT id FROM users WHERE email = :email LIMIT 1', ['email' => $email]);
    if (!$user) {
        return null;
    }

    $userId = (int) $user['id'];
    if ($role === 'seller') {
        sg_execute(
            'INSERT INTO kyc_verifications (user_id, nik, ktp_photo_path, selfie_photo_path, status, reviewed_at)
             VALUES (:user_id, :nik, :ktp, :selfie, "approved", NOW())',
            [
                'user_id' => $userId,
                'nik' => '320000000000000' . ($userId % 10),
                'ktp' => 'assets/images/national_id.png',
                'selfie' => 'assets/images/selfie_id.png',
            ]
        );
        $kyc = sg_fetch_one('SELECT id FROM kyc_verifications WHERE user_id = :user_id ORDER BY id DESC LIMIT 1', [
            'user_id' => $userId,
        ]);
        if ($kyc) {
            sg_execute(
                'INSERT INTO seller_profiles (user_id, kyc_id, total_sales, total_tickets_sold)
                 VALUES (:user_id, :kyc_id, 0, 0)',
                ['user_id' => $userId, 'kyc_id' => $kyc['id']]
            );
        }
    }

    return $userId;
}

function sg_ensure_demo_events(): void
{
    if (!sg_db()) {
        return;
    }

    $count = sg_fetch_one('SELECT COUNT(*) AS total FROM events');
    if ((int) ($count['total'] ?? 0) > 0) {
        return;
    }

    $events = [
        ['The Eras Tour - London', 'Wembley Stadium', 'London, UK', '2024-08-17 19:00:00', '19:00:00', 'Join Taylor Swift for a legendary journey through her musical eras.'],
        ['Coldplay Music of the Spheres', 'GBK Stadium', 'Jakarta', '2024-09-08 20:00:00', '20:00:00', 'Coldplay live concert with verified SafeGate access.'],
        ['Premier League: London Derby', 'London Stadium', 'London, UK', '2024-10-12 18:30:00', '18:30:00', 'Premium football seat with official listing verification.'],
    ];

    foreach ($events as $event) {
        sg_execute(
            'INSERT INTO events (title, venue, city, event_date, event_time, description)
             VALUES (:title, :venue, :city, :event_date, :event_time, :description)',
            [
                'title' => $event[0],
                'venue' => $event[1],
                'city' => $event[2],
                'event_date' => $event[3],
                'event_time' => $event[4],
                'description' => $event[5],
            ]
        );
    }
}

function sg_get_events_for_listing(): array
{
    $events = sg_fetch_all(
        'SELECT id, title, venue, city, event_date, event_time, description
         FROM events
         ORDER BY event_date ASC
         LIMIT 12'
    );

    if (!$events) {
        sg_ensure_demo_events();
        $events = sg_fetch_all(
            'SELECT id, title, venue, city, event_date, event_time, description
             FROM events
             ORDER BY event_date ASC
             LIMIT 12'
        );
        if (!$events) {
            return [
                [
                    'id' => '',
                    'title' => 'The Eras Tour - London',
                    'date' => 'August 17, 2024',
                    'time' => '19:00 BST',
                    'venue' => 'Wembley Stadium, London, UK',
                    'face_value' => 1500000,
                    'selling_price' => 1000000,
                    'description' => 'Join Taylor Swift for a legendary journey through her musical eras.',
                ],
            ];
        }
    }

    return array_map(static function (array $event, int $index): array {
        $faceValues = [1500000, 950000, 1800000, 1250000];
        $faceValue = $faceValues[$index % count($faceValues)];

        return [
            'id' => $event['id'],
            'title' => $event['title'],
            'date' => date('F d, Y', strtotime($event['event_date'])),
            'time' => substr((string) $event['event_time'], 0, 5),
            'venue' => trim($event['venue'] . ', ' . $event['city'], ', '),
            'face_value' => $faceValue,
            'selling_price' => min((int) round($faceValue * 1.05), (int) round($faceValue * 1.1)),
            'description' => $event['description'] ?: 'Official SafeGate verified event listing.',
        ];
    }, $events, array_keys($events));
}

function sg_get_seller_overview(?int $sellerId): array
{
    if (!$sellerId) {
        return [
            'escrow_balance' => 0,
            'available_balance' => 0,
            'sales_volume' => 0,
            'active_listings' => 0,
            'sold_month' => 0,
        ];
    }

    $row = sg_fetch_one(
        'SELECT
            COALESCE(SUM(CASE WHEN escrow_status IN ("holding", "disputed") THEN seller_earning ELSE 0 END), 0) AS escrow_balance,
            COALESCE(SUM(CASE WHEN escrow_status = "released" THEN seller_earning ELSE 0 END), 0) AS released_balance,
            COALESCE(SUM(seller_earning), 0) AS sales_volume,
            COALESCE(SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) THEN 1 ELSE 0 END), 0) AS sold_month
         FROM transactions
         WHERE seller_id = :seller_id',
        ['seller_id' => $sellerId]
    );

    $withdrawn = sg_fetch_one(
        'SELECT COALESCE(SUM(amount), 0) AS total FROM withdrawals WHERE seller_id = :seller_id AND status IN ("pending", "processing", "success")',
        ['seller_id' => $sellerId]
    );

    $active = sg_fetch_one(
        'SELECT COUNT(*) AS total FROM ticket_listings WHERE seller_id = :seller_id AND listing_status IN ("active", "promoted", "pending_review")',
        ['seller_id' => $sellerId]
    );

    $available = max(0, (int) ($row['released_balance'] ?? 0) - (int) ($withdrawn['total'] ?? 0));

    return [
        'escrow_balance' => (int) ($row['escrow_balance'] ?? 0),
        'available_balance' => $available,
        'sales_volume' => (int) ($row['sales_volume'] ?? 0),
        'active_listings' => (int) ($active['total'] ?? 0),
        'sold_month' => (int) ($row['sold_month'] ?? 0),
    ];
}

function sg_get_seller_listings(?int $sellerId): array
{
    $rows = $sellerId ? sg_fetch_all(
        'SELECT tl.*, e.title, e.venue, e.city,
            (SELECT COUNT(*) FROM bids b WHERE b.listing_id = tl.id) AS bid_count
         FROM ticket_listings tl
         JOIN events e ON e.id = tl.event_id
         WHERE tl.seller_id = :seller_id
         ORDER BY tl.created_at DESC',
        ['seller_id' => $sellerId]
    ) : [];

    if (!$rows) {
        return [];
    }

    return array_map(static function (array $row): array {
        return [
            'id' => $row['id'],
            'title' => $row['title'],
            'price' => sg_rupiah($row['current_highest_bid'] ?: $row['starting_bid']),
            'status' => sg_listing_status_label($row['listing_status']),
            'status_raw' => $row['listing_status'],
            'meta' => $row['venue'] . ' - ' . (int) $row['bid_count'] . ' bids',
        ];
    }, $rows);
}

function sg_get_buyer_tickets(?int $buyerId): array
{
    if (!$buyerId) {
        return [];
    }

    return sg_fetch_all(
        'SELECT t.id AS transaction_id, t.transaction_code, t.total_amount, t.payment_status, t.escrow_status, t.created_at,
                tl.section, tl.row, tl.seat, e.title, e.venue, e.city, e.event_date, e.image_path
                ,(SELECT d.status FROM disputes d WHERE d.transaction_id = t.id ORDER BY d.opened_at DESC LIMIT 1) AS dispute_status
         FROM transactions t
         JOIN ticket_listings tl ON tl.id = t.listing_id
         JOIN events e ON e.id = tl.event_id
         WHERE t.buyer_id = :buyer_id
         ORDER BY t.created_at DESC',
        ['buyer_id' => $buyerId]
    );
}

function sg_get_seller_withdrawals(?int $sellerId): array
{
    $rows = $sellerId ? sg_fetch_all(
        'SELECT method, amount, status, created_at FROM withdrawals WHERE seller_id = :seller_id ORDER BY created_at DESC LIMIT 8',
        ['seller_id' => $sellerId]
    ) : [];

    if (!$rows) {
        return [];
    }

    return array_map(static function (array $row): array {
        $class = $row['status'] === 'pending' ? 'processing' : $row['status'];

        return [
            'date' => date('d M', strtotime($row['created_at'])) . '<br>' . date('Y', strtotime($row['created_at'])),
            'method' => str_replace(' ', '<br>', sg_method_label($row['method'])),
            'amount' => str_replace(' ', '<br>', sg_rupiah($row['amount'])),
            'status' => ucwords($row['status']),
            'class' => $class,
        ];
    }, $rows);
}

function sg_seller_transaction_filters(?int $sellerId, array $filters = []): array
{
    $params = ['seller_id' => $sellerId, 'buyer_id' => $sellerId];
    $where = ['(t.seller_id = :seller_id OR t.buyer_id = :buyer_id)'];

    $search = trim((string) ($filters['q'] ?? ''));
    if ($search !== '') {
        $where[] = '(t.transaction_code LIKE :search OR e.title LIKE :search)';
        $params['search'] = '%' . $search . '%';
    }

    $dateRange = (string) ($filters['date_range'] ?? 'Last 30 Days');
    if ($dateRange === 'Last 90 Days') {
        $where[] = 't.created_at >= DATE_SUB(NOW(), INTERVAL 90 DAY)';
    } elseif ($dateRange === 'This Year') {
        $where[] = 'YEAR(t.created_at) = YEAR(CURDATE())';
    } else {
        $where[] = 't.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)';
    }

    $status = (string) ($filters['status'] ?? 'All Status');
    if ($status === 'Completed') {
        $where[] = 't.payment_status = "paid" AND t.escrow_status = "released"';
    } elseif ($status === 'Pending') {
        $where[] = '(t.payment_status = "pending" OR t.escrow_status IN ("holding", "disputed"))';
    } elseif ($status === 'Cancelled') {
        $where[] = '(t.payment_status IN ("failed", "refunded") OR t.escrow_status = "refunded")';
    }

    return [$where, $params, $search, $dateRange, $status];
}

function sg_get_seller_transactions(?int $sellerId, array $filters = []): array
{
    [$where, $params, $search, $dateRange, $status] = sg_seller_transaction_filters($sellerId, $filters);

    if (!$sellerId) {
        return [
            'summary' => ['buy' => sg_rupiah(0), 'sell' => sg_rupiah(0)],
            'transactions' => [],
            'total' => 0,
        ];
    }

    $summaryParams = $params + [
        'summary_seller_id' => $sellerId,
        'summary_buyer_id' => $sellerId,
    ];
    $totalRow = sg_fetch_one(
        'SELECT COUNT(*) AS total,
                COALESCE(SUM(CASE WHEN t.seller_id = :summary_seller_id THEN t.seller_earning ELSE 0 END), 0) AS total_sell,
                COALESCE(SUM(CASE WHEN t.buyer_id = :summary_buyer_id THEN t.total_amount ELSE 0 END), 0) AS total_buy
         FROM transactions t
         JOIN ticket_listings tl ON tl.id = t.listing_id
         JOIN events e ON e.id = tl.event_id
         WHERE ' . implode(' AND ', $where),
        $summaryParams
    );
    $totalRows = (int) ($totalRow['total'] ?? 0);
    $totalBuy = (int) ($totalRow['total_buy'] ?? 0);
    $totalSell = (int) ($totalRow['total_sell'] ?? 0);

    $limit = max(1, min(100, (int) ($filters['limit'] ?? 20)));
    $offset = max(0, (int) ($filters['offset'] ?? 0));

    $rows = sg_fetch_all(
        'SELECT t.*, e.title, e.description
         FROM transactions t
         JOIN ticket_listings tl ON tl.id = t.listing_id
         JOIN events e ON e.id = tl.event_id
         WHERE ' . implode(' AND ', $where) . '
         ORDER BY t.created_at DESC
         LIMIT ' . $limit . ' OFFSET ' . $offset,
        $params
    );

    $hasActiveFilter = $search !== '' || $dateRange !== 'Last 30 Days' || $status !== 'All Status';
    if (!$rows && $hasActiveFilter) {
        return [
            'summary' => ['buy' => sg_rupiah($totalBuy), 'sell' => sg_rupiah($totalSell)],
            'transactions' => [],
            'total' => $totalRows,
        ];
    }

    if (!$rows) {
        return [
            'summary' => ['buy' => sg_rupiah($totalBuy), 'sell' => sg_rupiah($totalSell)],
            'transactions' => [],
            'total' => $totalRows,
        ];
    }

    $transactions = [];

    foreach ($rows as $row) {
        $isSell = (int) $row['seller_id'] === (int) $sellerId;

        $transactions[] = [
            'title' => $row['title'],
            'id' => $row['transaction_code'],
            'date' => date('M d, Y', strtotime($row['created_at'])),
            'time' => date('H:i A', strtotime($row['created_at'])),
            'type' => $isSell ? 'SELL' : 'BUY',
            'amount' => sg_rupiah($isSell ? $row['seller_earning'] : $row['total_amount']),
            'note' => sg_transaction_note($row['payment_status'], $row['escrow_status']),
            'status' => sg_transaction_status_label($row['payment_status'], $row['escrow_status']),
            'status_class' => sg_payment_status_class($row['payment_status'], $row['escrow_status']),
            'thumb' => sg_event_thumb_class($row['title'], $row['description'] ?? ''),
        ];
    }

    return [
        'summary' => ['buy' => sg_rupiah($totalBuy), 'sell' => sg_rupiah($totalSell)],
        'transactions' => $transactions,
        'total' => $totalRows,
    ];
}

function sg_get_seller_profile(?int $sellerId): array
{
    $profile = $sellerId ? sg_fetch_one(
        'SELECT u.full_name, u.email, u.phone_number, u.nik, sp.trust_score, kv.status AS kyc_status
         FROM users u
         LEFT JOIN seller_profiles sp ON sp.user_id = u.id
         LEFT JOIN kyc_verifications kv ON kv.id = sp.kyc_id
         WHERE u.id = :id
         LIMIT 1',
        ['id' => $sellerId]
    ) : null;

    return $profile ?: [
        'full_name' => 'John Doe Institutional',
        'email' => 'john.doe@safegate.corp',
        'phone_number' => '+62 812 3456 7890',
        'nik' => '',
        'trust_score' => 100,
        'kyc_status' => 'pending',
    ];
}

function sg_get_admin_overview(): array
{
    $kpis = sg_fetch_one(
        'SELECT
            COALESCE(SUM(CASE WHEN escrow_status IN ("holding", "disputed") THEN total_amount ELSE 0 END), 0) AS escrow_locked,
            COALESCE(SUM(platform_revenue), 0) AS revenue
         FROM transactions'
    );
    $pendingKyc = sg_fetch_one('SELECT COUNT(*) AS total FROM kyc_verifications WHERE status = "pending"');
    $disputes = sg_fetch_one('SELECT COUNT(*) AS total FROM disputes WHERE status IN ("open", "under_review")');

    return [
        'escrow_locked' => (int) ($kpis['escrow_locked'] ?? 0),
        'revenue' => (int) ($kpis['revenue'] ?? 0),
        'pending_kyc' => (int) ($pendingKyc['total'] ?? 0),
        'active_disputes' => (int) ($disputes['total'] ?? 0),
    ];
}

function sg_get_admin_kyc_submissions(string $assetsPath, string $status = 'pending'): array
{
    $params = [];
    $where = '';
    if (in_array($status, ['pending', 'approved', 'rejected'], true)) {
        $where = 'WHERE kv.status = :status';
        $params['status'] = $status;
    }

    $rows = sg_fetch_all(
        'SELECT kv.id, kv.nik, kv.ktp_photo_path, kv.selfie_photo_path, kv.status, kv.submitted_at,
                u.full_name, u.email
         FROM kyc_verifications kv
         JOIN users u ON u.id = kv.user_id
         ' . $where . '
         ORDER BY kv.submitted_at DESC
         LIMIT 30',
        $params
    );

    if (!$rows) {
        return [];
    }

    return array_map(static function (array $row) use ($assetsPath): array {
        $ktpImage = (string) ($row['ktp_photo_path'] ?: $assetsPath . '/images/national_id.png');
        $selfieImage = (string) ($row['selfie_photo_path'] ?? '');
        $hasSelfie = $selfieImage !== '' && $selfieImage !== $row['ktp_photo_path'];

        return [
            'id' => (string) $row['id'],
            'name' => $row['full_name'],
            'nik' => $row['nik'],
            'email' => $row['email'],
            'path' => 'Database KYC Queue',
            'time' => sg_time_ago($row['submitted_at']),
            'status' => strtoupper($row['status']),
            'ktp_img' => $ktpImage,
            'selfie_img' => $hasSelfie ? $selfieImage : '',
            'has_selfie' => $hasSelfie,
        ];
    }, $rows);
}

function sg_get_admin_action_queue(int $limit = 8): array
{
    $queue = [];

    $kycRows = sg_fetch_all(
        'SELECT kv.id, kv.submitted_at, u.full_name, u.email
         FROM kyc_verifications kv
         JOIN users u ON u.id = kv.user_id
         WHERE kv.status = "pending"
         ORDER BY kv.submitted_at DESC
         LIMIT 4'
    );

    foreach ($kycRows as $row) {
        $queue[] = [
            'timestamp' => $row['submitted_at'],
            'type' => 'KYC REQUEST',
            'class' => 'is-kyc',
            'description' => $row['full_name'] . ' - ' . $row['email'],
            'action_label' => 'Review',
            'action_class' => 'is-green',
            'action_link' => 'index.php?page=admin_kyc',
            'icon' => 'ph:identification-card',
        ];
    }

    $disputeRows = sg_fetch_all(
        'SELECT d.id, d.opened_at, d.buyer_claim, t.transaction_code
         FROM disputes d
         JOIN transactions t ON t.id = d.transaction_id
         WHERE d.status IN ("open", "under_review")
         ORDER BY d.opened_at DESC
         LIMIT 4'
    );

    foreach ($disputeRows as $row) {
        $claim = (string) $row['buyer_claim'];
        $claimPreview = strlen($claim) > 72 ? substr($claim, 0, 72) . '...' : $claim;
        $queue[] = [
            'timestamp' => $row['opened_at'],
            'type' => 'ESCROW DISPUTE',
            'class' => 'is-dispute',
            'description' => $row['transaction_code'] . ' - ' . $claimPreview,
            'action_label' => 'Investigate',
            'action_class' => 'is-peach',
            'action_link' => 'index.php?page=admin_disputes',
            'icon' => 'ph:warning',
        ];
    }

    $auditRows = sg_fetch_all(
        'SELECT action, target_type, target_id, notes, created_at
         FROM admin_audit_logs
         ORDER BY created_at DESC
         LIMIT 6'
    );

    foreach ($auditRows as $row) {
        $queue[] = [
            'timestamp' => $row['created_at'],
            'type' => 'AUDIT LOG',
            'class' => 'is-audit',
            'description' => ($row['notes'] ?: ucwords(str_replace('_', ' ', $row['action']))) . ' #' . $row['target_id'],
            'action_label' => 'View',
            'action_class' => '',
            'action_link' => $row['target_type'] === 'transaction' ? 'index.php?page=admin_transactions' : 'index.php?page=admin_overview',
            'icon' => 'ph:dots-three-outline-fill',
        ];
    }

    usort($queue, static function (array $a, array $b): int {
        return strtotime($b['timestamp'] ?? '') <=> strtotime($a['timestamp'] ?? '');
    });

    return array_slice($queue, 0, max(1, $limit));
}

function sg_admin_transaction_filters(array $filters = []): array
{
    $where = [];
    $params = [];

    if (!empty($filters['search'])) {
        $where[] = '(t.transaction_code LIKE :search OR buyer.email LIKE :search OR seller.email LIKE :search OR e.title LIKE :search)';
        $params['search'] = '%' . $filters['search'] . '%';
    }
    if (!empty($filters['payment_status']) && $filters['payment_status'] !== 'all') {
        $where[] = 't.payment_status = :payment_status';
        $params['payment_status'] = $filters['payment_status'];
    }
    if (!empty($filters['escrow_status']) && $filters['escrow_status'] !== 'all') {
        $where[] = 't.escrow_status = :escrow_status';
        $params['escrow_status'] = $filters['escrow_status'];
    }
    if (!empty($filters['date'])) {
        $where[] = 'DATE(t.created_at) = :date';
        $params['date'] = $filters['date'];
    }

    return [$where, $params];
}

function sg_count_admin_transactions(array $filters = []): int
{
    [$where, $params] = sg_admin_transaction_filters($filters);

    $sql = 'SELECT COUNT(*) AS total
            FROM transactions t
            JOIN users buyer ON buyer.id = t.buyer_id
            JOIN users seller ON seller.id = t.seller_id
            JOIN ticket_listings tl ON tl.id = t.listing_id
            JOIN events e ON e.id = tl.event_id';
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $row = sg_fetch_one($sql, $params);
    return (int) ($row['total'] ?? 0);
}

function sg_get_admin_transactions(array $filters = []): array
{
    [$where, $params] = sg_admin_transaction_filters($filters);

    $sql = 'SELECT t.*, e.title AS event_title, buyer.email AS buyer_email, seller.full_name AS seller_name
            FROM transactions t
            JOIN users buyer ON buyer.id = t.buyer_id
            JOIN users seller ON seller.id = t.seller_id
            JOIN ticket_listings tl ON tl.id = t.listing_id
            JOIN events e ON e.id = tl.event_id';
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $limit = max(1, min(500, (int) ($filters['limit'] ?? 50)));
    $offset = max(0, (int) ($filters['offset'] ?? 0));
    $sql .= ' ORDER BY t.created_at DESC LIMIT ' . $limit . ' OFFSET ' . $offset;

    return sg_fetch_all($sql, $params);
}

function sg_get_admin_disputes(): array
{
    $rows = sg_fetch_all(
        'SELECT d.*, t.transaction_code, t.total_amount, buyer.full_name AS buyer_name, seller.full_name AS seller_name
         FROM disputes d
         JOIN transactions t ON t.id = d.transaction_id
         JOIN users buyer ON buyer.id = d.buyer_id
         JOIN users seller ON seller.id = d.seller_id
         ORDER BY d.opened_at DESC'
    );

    if (!$rows) {
        return [];
    }

    return array_map(static function (array $row): array {
        return [
            'id' => 'SG-' . $row['id'],
            'item' => $row['transaction_code'],
            'amount' => sg_rupiah($row['total_amount']),
            'pool' => number_format((int) $row['total_amount'], 0, ',', '.') . ' IDR',
            'status' => in_array($row['status'], ['open', 'under_review'], true) ? 'FROZEN' : 'RESOLVED',
            'reported_by' => 'Reported by ' . $row['buyer_name'],
            'updated_time' => 'Updated ' . sg_time_ago($row['opened_at']),
            'buyer_claim' => $row['buyer_claim'],
            'seller_defense' => $row['seller_defense'] ?: 'Seller belum mengirim pembelaan.',
            'ip_origin' => $row['ip_origin'],
            'wallet_age' => 'Verified',
            'trust_score' => '98%',
            'auth_level' => 'Database-Verified',
            'admin_id' => '#' . ($row['handled_by_admin'] ?: 'AUTO'),
            'detail_link' => 'index.php?page=transaction_detail&code=' . urlencode($row['transaction_code']),
        ];
    }, $rows);
}

function sg_get_marketplace_listings(array $filters = []): array
{
    $params = [];
    $where = ['tl.listing_status IN ("active", "promoted")'];
    $search = trim((string) ($filters['q'] ?? ''));
    if ($search !== '') {
        $where[] = '(e.title LIKE :search OR e.venue LIKE :search OR e.city LIKE :search OR e.description LIKE :search)';
        $params['search'] = '%' . $search . '%';
    }

    $location = trim((string) ($filters['location'] ?? ''));
    if ($location !== '') {
        $where[] = '(e.venue LIKE :location OR e.city LIKE :location)';
        $params['location'] = '%' . $location . '%';
    }

    $date = trim((string) ($filters['date'] ?? ''));
    if ($date !== '') {
        $where[] = 'DATE(e.event_date) = :event_date';
        $params['event_date'] = $date;
    }

    $category = (string) ($filters['category'] ?? 'all');
    if ($category === 'sports') {
        $where[] = '(e.title LIKE "%NBA%" OR e.title LIKE "%League%" OR e.title LIKE "%Final%" OR e.description LIKE "%sports%")';
    } elseif ($category === 'festival') {
        $where[] = '(e.title LIKE "%Festival%" OR e.description LIKE "%festival%")';
    } elseif ($category === 'concert') {
        $where[] = '(e.title LIKE "%Tour%" OR e.title LIKE "%Music%" OR e.title LIKE "%Konser%" OR e.description LIKE "%music%")';
    }

    $sort = (string) ($filters['sort'] ?? 'featured');
    $order = 'tl.is_promoted DESC, tl.created_at DESC';
    if ($sort === 'price-asc') {
        $order = 'COALESCE(tl.current_highest_bid, tl.starting_bid) ASC';
    } elseif ($sort === 'price-desc') {
        $order = 'COALESCE(tl.current_highest_bid, tl.starting_bid) DESC';
    } elseif ($sort === 'date') {
        $order = 'e.event_date ASC';
    }

    $rows = sg_fetch_all(
        'SELECT tl.id, tl.section, tl.row, tl.seat, tl.face_value, tl.starting_bid, tl.current_highest_bid, tl.listing_status,
                e.title, e.venue, e.city, e.event_date, e.image_path, e.description
         FROM ticket_listings tl
         JOIN events e ON e.id = tl.event_id
         WHERE ' . implode(' AND ', $where) . '
         ORDER BY ' . $order,
        $params
    );

    if (!$rows) {
        return [];
    }

    return array_map(static function (array $row): array {
        $price = (int) ($row['current_highest_bid'] ?: $row['starting_bid']);

        return [
            'id' => $row['id'],
            'title' => $row['title'],
            'image' => sg_event_image($row['title'], $row['image_path'], $row['description']),
            'date' => date('F d, Y', strtotime($row['event_date'])) . ' - ' . $row['venue'] . ', ' . $row['city'],
            'price' => number_format($price, 0, ',', '.'),
            'originalPrice' => number_format((int) $row['face_value'], 0, ',', '.'),
            'section' => $row['section'],
            'row' => $row['row'],
            'seat' => $row['seat'],
        ];
    }, $rows);
}

function sg_get_listing_detail(int $listingId): ?array
{
    return sg_fetch_one(
        'SELECT tl.*, e.title, e.venue, e.city, e.event_date, e.image_path, e.description, seller.full_name AS seller_name
         FROM ticket_listings tl
         JOIN events e ON e.id = tl.event_id
         JOIN users seller ON seller.id = tl.seller_id
         WHERE tl.id = :id
         LIMIT 1',
        ['id' => $listingId]
    );
}

function sg_get_listing_bids(int $listingId): array
{
    if ($listingId <= 0) {
        return [];
    }

    return sg_fetch_all(
        'SELECT b.id, b.bid_amount, b.is_winning_bid, b.created_at, u.full_name
         FROM bids b
         JOIN users u ON u.id = b.bidder_id
         WHERE b.listing_id = :listing_id
         ORDER BY b.bid_amount DESC, b.created_at DESC
         LIMIT 5',
        ['listing_id' => $listingId]
    );
}

function sg_get_transaction_detail(string $code): ?array
{
    return sg_fetch_one(
        'SELECT t.*, e.title, e.venue, e.city, e.event_date, tl.section, tl.row, tl.seat,
                buyer.full_name AS buyer_name, buyer.email AS buyer_email,
                seller.full_name AS seller_name, seller.email AS seller_email
         FROM transactions t
         JOIN ticket_listings tl ON tl.id = t.listing_id
         JOIN events e ON e.id = tl.event_id
         JOIN users buyer ON buyer.id = t.buyer_id
         JOIN users seller ON seller.id = t.seller_id
         WHERE t.transaction_code = :code
         LIMIT 1',
        ['code' => $code]
    );
}

function sg_get_transaction_ledger(int $transactionId): array
{
    if ($transactionId <= 0) {
        return [];
    }

    return sg_fetch_all(
        'SELECT el.*, el.type AS entry_type, u.full_name, u.role
         FROM escrow_ledger el
         JOIN users u ON u.id = el.user_id
         WHERE el.transaction_id = :transaction_id
         ORDER BY el.created_at ASC, el.id ASC',
        ['transaction_id' => $transactionId]
    );
}

function sg_get_transaction_disputes(int $transactionId): array
{
    if ($transactionId <= 0) {
        return [];
    }

    return sg_fetch_all(
        'SELECT d.*, admin.full_name AS admin_name
         FROM disputes d
         LEFT JOIN users admin ON admin.id = d.handled_by_admin
         WHERE d.transaction_id = :transaction_id
         ORDER BY d.opened_at DESC',
        ['transaction_id' => $transactionId]
    );
}

function sg_get_dispute_messages(int $disputeId): array
{
    if ($disputeId <= 0) {
        return [];
    }

    return sg_fetch_all(
        'SELECT dm.*, u.full_name
         FROM dispute_messages dm
         JOIN users u ON u.id = dm.sender_id
         WHERE dm.dispute_id = :dispute_id
         ORDER BY dm.created_at ASC',
        ['dispute_id' => $disputeId]
    );
}
