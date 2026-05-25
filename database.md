1. Visualisasi ERD (Entity Relationship Diagram)

Salin kode di bawah ini ke mermaid.live atau Markdown viewer yang mendukung Mermaid untuk melihat visualisasi relasi database.

erDiagram
    %% USER & IDENTITY MANAGEMENT
    users ||--o{ passkeys : "has"
    users ||--o{ kyc_verifications : "submits"
    users ||--o| seller_profiles : "upgrades to (1-to-1)"
    users ||--o{ notifications : "receives"
    
    %% CORE TICKET MARKETPLACE
    events ||--o{ ticket_listings : "contains"
    users ||--o{ ticket_listings : "sells"
    ticket_listings ||--o{ bids : "receives"
    users ||--o{ bids : "places bid"
    
    %% TRANSACTIONS & ESCROW
    ticket_listings ||--o| transactions : "results in"
    bids |o--o| transactions : "winning bid (optional)"
    users ||--o{ transactions : "buys/sells"
    transactions ||--o{ escrow_ledger : "tracks"
    users ||--o{ withdrawals : "requests"
    
    %% DISPUTES & ADMIN
    transactions ||--o| disputes : "can have"
    users ||--o{ disputes : "reports/handles"
    disputes ||--o{ dispute_messages : "contains"
    users ||--o{ dispute_messages : "sends"
    users ||--o{ admin_audit_logs : "performs (admin)"

    2. Rincian Struktur Tabel (Data Dictionary)

    ### 1. `users` (Sentral Identitas)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT PK | AUTO_INCREMENT |
| `full_name` | VARCHAR(150) | |
| `email` | VARCHAR(150) | UNIQUE |
| `phone_number` | VARCHAR(20) | |
| `nik` | VARCHAR(16) | UNIQUE, NULLABLE (Diisi saat KYC) |
| `password_hash` | VARCHAR(255) | Bcrypt hash |
| `role` | ENUM | 'buyer', 'seller', 'admin' |
| `is_active` | TINYINT(1) | DEFAULT 1 |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

### 2. `passkeys` (Keamanan Biometrik)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT PK | AUTO_INCREMENT |
| `user_id` | BIGINT FK | -> `users.id` |
| `credential_id` | VARCHAR(500) | UNIQUE |
| `public_key` | TEXT | |
| `device_name` | VARCHAR(100) | Misal: "iPhone 15" |
| `created_at` | TIMESTAMP | |
| `last_used_at` | TIMESTAMP | |

### 3. `kyc_verifications` (Validasi Identitas)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT PK | AUTO_INCREMENT |
| `user_id` | BIGINT FK | -> `users.id` |
| `nik` | VARCHAR(16) | |
| `ktp_photo_path` | VARCHAR(500) | URL/Path ke storage |
| `selfie_photo_path`| VARCHAR(500) | URL/Path ke storage |
| `status` | ENUM | 'pending', 'approved', 'rejected' (Def: 'pending') |
| `reviewed_by` | BIGINT FK | -> `users.id` (Admin), NULLABLE |
| `rejection_reason`| TEXT | NULLABLE |
| `submitted_at` | TIMESTAMP | |
| `reviewed_at` | TIMESTAMP | NULLABLE |

### 4. `seller_profiles` (Dompet & Reputasi Vendor)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT PK | AUTO_INCREMENT |
| `user_id` | BIGINT FK | -> `users.id` (UNIQUE) |
| `kyc_id` | BIGINT FK | -> `kyc_verifications.id` |
| `trust_score` | DECIMAL(5,2)| DEFAULT 100.00 (Max 100) |
| `total_sales` | BIGINT | DEFAULT 0 (Rupiah) |
| `total_tickets_sold`| INT | DEFAULT 0 |
| `bank_name` | VARCHAR(100) | NULLABLE |
| `account_number` | VARCHAR(50) | NULLABLE |
| `account_holder_name`|VARCHAR(150)| NULLABLE |
| `ewallet_type` | VARCHAR(50) | NULLABLE (DANA/OVO) |
| `ewallet_number` | VARCHAR(50) | NULLABLE |
| `crypto_address` | VARCHAR(200) | NULLABLE |
| `created_at` | TIMESTAMP | |

### 5. `events` (Data Konser/Acara Resmi)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT PK | AUTO_INCREMENT |
| `title` | VARCHAR(200) | Nama event |
| `venue` | VARCHAR(200) | Nama venue |
| `city` | VARCHAR(100) | |
| `event_date` | DATETIME | |
| `event_time` | TIME | |
| `image_path` | VARCHAR(500) | NULLABLE |
| `description` | TEXT | NULLABLE |
| `created_at` | TIMESTAMP | |

### 6. `ticket_listings` (Etalase Aset Tiket)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT PK | AUTO_INCREMENT |
| `seller_id` | BIGINT FK | -> `users.id` |
| `event_id` | BIGINT FK | -> `events.id` |
| `section` | VARCHAR(20) | Nomor seksi kursi |
| `row` | VARCHAR(10) | Baris kursi |
| `seat` | VARCHAR(10) | Nomor kursi |
| `face_value` | BIGINT | Harga asli (Untuk Price Ceiling) |
| `starting_bid` | BIGINT | Harga mulai lelang / Harga Pas |
| `reserve_price` | BIGINT | Harga minimum, NULLABLE |
| `current_highest_bid`| BIGINT | Update otomatis, NULLABLE |
| `auction_duration_hours`| INT | NULLABLE (Bisa 24/72/168 jam) |
| `auction_end_at` | DATETIME | NULLABLE |
| `ticket_proof_path` | VARCHAR(500) | Path file PDF/foto tiket |
| `listing_status` | ENUM | 'pending_review', 'active', 'paused', 'sold', 'cancelled', 'promoted' |
| `is_promoted` | TINYINT(1) | DEFAULT 0 |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

### 7. `bids` (Log Penawaran Lelang)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT PK | AUTO_INCREMENT |
| `listing_id` | BIGINT FK | -> `ticket_listings.id` |
| `bidder_id` | BIGINT FK | -> `users.id` |
| `bid_amount` | BIGINT | Nominal tawaran |
| `is_winning_bid` | TINYINT(1) | DEFAULT 0 |
| `ip_address` | VARCHAR(45) | Metadata untuk audit |
| `created_at` | TIMESTAMP | |

### 8. `transactions` (Global Ledger Pembayaran)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT PK | AUTO_INCREMENT |
| `transaction_code` | VARCHAR(30) | UNIQUE (Format: SG-TX-XXXXXX) |
| `listing_id` | BIGINT FK | -> `ticket_listings.id` |
| `buyer_id` | BIGINT FK | -> `users.id` |
| `seller_id` | BIGINT FK | -> `users.id` |
| `winning_bid_id` | BIGINT FK | -> `bids.id`, **NULLABLE** (Jika Fixed Price) |
| `base_price` | BIGINT | Harga final / menang lelang |
| `service_fee` | BIGINT | Misal: 5% dari base_price |
| `escrow_insurance` | BIGINT | Misal: 11% dari base_price |
| `total_amount` | BIGINT | Total dibayar pembeli |
| `platform_revenue` | BIGINT | Pendapatan bersih SafeGate |
| `seller_earning` | BIGINT | Yang akan diterima penjual |
| `payment_method` | ENUM | 'bank_transfer', 'dana', 'gopay', 'ovo', 'usdc' |
| `payment_status` | ENUM | 'pending', 'paid', 'failed', 'refunded' (Def: 'pending') |
| `escrow_status` | ENUM | 'holding', 'released', 'refunded', 'disputed' (Def: 'holding') |
| `paid_at` | TIMESTAMP | NULLABLE |
| `escrow_released_at`| TIMESTAMP | NULLABLE |
| `created_at` | TIMESTAMP | |

### 9. `escrow_ledger` (Log Mutasi Escrow)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT PK | AUTO_INCREMENT |
| `transaction_id` | BIGINT FK | -> `transactions.id` |
| `user_id` | BIGINT FK | -> `users.id` |
| `type` | ENUM | 'lock', 'release', 'refund', 'fee_deduct' |
| `amount` | BIGINT | |
| `balance_after` | BIGINT | Saldo escrow penjual setelah mutasi |
| `notes` | VARCHAR(255) | NULLABLE |
| `created_at` | TIMESTAMP | |

### 10. `withdrawals` (Penarikan Dana Penjual)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT PK | AUTO_INCREMENT |
| `seller_id` | BIGINT FK | -> `users.id` |
| `amount` | BIGINT | |
| `method` | ENUM | 'bank_transfer', 'dana', 'gopay', 'ovo', 'usdc' |
| `destination_account`|VARCHAR(200)| Rekening / Address tujuan |
| `status` | ENUM | 'pending', 'processing', 'success', 'failed' |
| `processed_at` | TIMESTAMP | NULLABLE |
| `created_at` | TIMESTAMP | |

### 11. `disputes` (Pusat Resolusi Sengketa)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT PK | AUTO_INCREMENT |
| `transaction_id` | BIGINT FK | -> `transactions.id` |
| `buyer_id` | BIGINT FK | -> `users.id` |
| `seller_id` | BIGINT FK | -> `users.id` |
| `handled_by_admin` | BIGINT FK | -> `users.id`, NULLABLE |
| `status` | ENUM | 'open', 'under_review', 'resolved_refund', 'resolved_release', 'closed' |
| `buyer_claim` | TEXT | |
| `seller_defense` | TEXT | NULLABLE |
| `admin_notes` | TEXT | NULLABLE |
| `ip_origin` | VARCHAR(45) | Metadata untuk forensik |
| `resolution` | ENUM | 'refund_buyer', 'release_seller', 'partial', NULLABLE |
| `opened_at` | TIMESTAMP | |
| `resolved_at` | TIMESTAMP | NULLABLE |

### 12. `dispute_messages` (Log Diskusi Sengketa)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT PK | AUTO_INCREMENT |
| `dispute_id` | BIGINT FK | -> `disputes.id` |
| `sender_id` | BIGINT FK | -> `users.id` |
| `sender_role` | ENUM | 'buyer', 'seller', 'admin' |
| `message` | TEXT | |
| `attachment_path` | VARCHAR(500) | NULLABLE |
| `created_at` | TIMESTAMP | |

### 13. `notifications` (Sistem Notifikasi Global)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT PK | AUTO_INCREMENT |
| `user_id` | BIGINT FK | -> `users.id` |
| `type` | ENUM | 'bid_placed', 'auction_won', 'kyc_approved', 'dispute_opened', dll. |
| `title` | VARCHAR(200) | |
| `body` | TEXT | |
| `is_read` | TINYINT(1) | DEFAULT 0 |
| `related_id` | BIGINT | NULLABLE (ID Transaksi/Listing terkait) |
| `created_at` | TIMESTAMP | |

### 14. `admin_audit_logs` (Keamanan Super Admin)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT PK | AUTO_INCREMENT |
| `admin_id` | BIGINT FK | -> `users.id` |
| `action` | VARCHAR(100) | Contoh: "approve_kyc", "force_refund" |
| `target_type` | VARCHAR(50) | Nama tabel target (contoh: "disputes") |
| `target_id` | BIGINT | ID dari record yang diubah |
| `notes` | TEXT | NULLABLE |
| `ip_address` | VARCHAR(45) | |
| `created_at` | TIMESTAMP | |