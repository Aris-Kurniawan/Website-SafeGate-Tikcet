

-- 2. EKOSISTEM JANTUNG MARKETPLACE (THE CORE ENGINE)


CREATE TABLE ticket_listings (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    seller_id BIGINT NOT NULL,
    event_id BIGINT NOT NULL,
    section VARCHAR(20) NOT NULL,
    `row` VARCHAR(10) NOT NULL,
    seat VARCHAR(10) NOT NULL,
    face_value BIGINT NOT NULL,
    starting_bid BIGINT NOT NULL,
    reserve_price BIGINT NULL,
    current_highest_bid BIGINT NULL,
    auction_duration_hours INT NULL,
    auction_end_at DATETIME NULL,
    ticket_proof_path VARCHAR(500) NOT NULL,
    listing_status ENUM('pending_review', 'active', 'paused', 'sold', 'cancelled', 'promoted') DEFAULT 'pending_review',
    is_promoted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE RESTRICT
);

CREATE TABLE bids (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    listing_id BIGINT NOT NULL,
    bidder_id BIGINT NOT NULL,
    bid_amount BIGINT NOT NULL,
    is_winning_bid TINYINT(1) DEFAULT 0,
    ip_address VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (listing_id) REFERENCES ticket_listings(id) ON DELETE CASCADE,
    FOREIGN KEY (bidder_id) REFERENCES users(id) ON DELETE CASCADE
);


-- 3. EKOSISTEM ALIRAN UANG (THE MONEY PIPELINE)

CREATE TABLE transactions (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    transaction_code VARCHAR(30) UNIQUE NOT NULL,
    listing_id BIGINT NOT NULL,
    buyer_id BIGINT NOT NULL,
    seller_id BIGINT NOT NULL,
    winning_bid_id BIGINT NULL, -- BISA KOSONG JIKA BELI HARGA PAS (FIXED PRICE)
    base_price BIGINT NOT NULL,
    service_fee BIGINT NOT NULL,
    escrow_insurance BIGINT NOT NULL,
    total_amount BIGINT NOT NULL,
    platform_revenue BIGINT NOT NULL,
    seller_earning BIGINT NOT NULL,
    payment_method ENUM('bank_transfer', 'dana', 'gopay', 'ovo', 'usdc') NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    escrow_status ENUM('holding', 'released', 'refunded', 'disputed') DEFAULT 'holding',
    paid_at TIMESTAMP NULL,
    escrow_released_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (listing_id) REFERENCES ticket_listings(id) ON DELETE RESTRICT,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (winning_bid_id) REFERENCES bids(id) ON DELETE SET NULL
);

CREATE TABLE escrow_ledger (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    transaction_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    type ENUM('lock', 'release', 'refund', 'fee_deduct') NOT NULL,
    amount BIGINT NOT NULL,
    balance_after BIGINT NOT NULL,
    notes VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE withdrawals (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    seller_id BIGINT NOT NULL,
    amount BIGINT NOT NULL,
    method ENUM('bank_transfer', 'dana', 'gopay', 'ovo', 'usdc') NOT NULL,
    destination_account VARCHAR(200) NOT NULL,
    status ENUM('pending', 'processing', 'success', 'failed') DEFAULT 'pending',
    processed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE RESTRICT
);

-- 4. EKOSISTEM PENGADILAN ADMIN (THE DISPUTES)

CREATE TABLE disputes (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    transaction_id BIGINT NOT NULL,
    buyer_id BIGINT NOT NULL,
    seller_id BIGINT NOT NULL,
    handled_by_admin BIGINT NULL,
    status ENUM('open', 'under_review', 'resolved_refund', 'resolved_release', 'closed') DEFAULT 'open',
    buyer_claim TEXT NOT NULL,
    seller_defense TEXT NULL,
    admin_notes TEXT NULL,
    ip_origin VARCHAR(45) NOT NULL,
    resolution ENUM('refund_buyer', 'release_seller', 'partial') NULL,
    opened_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE RESTRICT,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (handled_by_admin) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE dispute_messages (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    dispute_id BIGINT NOT NULL,
    sender_id BIGINT NOT NULL,
    sender_role ENUM('buyer', 'seller', 'admin') NOT NULL,
    message TEXT NOT NULL,
    attachment_path VARCHAR(500) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dispute_id) REFERENCES disputes(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE notifications (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    type ENUM('bid_placed', 'auction_won', 'auction_lost', 'payment_success', 'kyc_approved', 'kyc_rejected', 'withdrawal_success', 'dispute_opened', 'escrow_released') NOT NULL,
    title VARCHAR(200) NOT NULL,
    body TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    related_id BIGINT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE admin_audit_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    admin_id BIGINT NOT NULL,
    action VARCHAR(100) NOT NULL,
    target_type VARCHAR(50) NOT NULL,
    target_id BIGINT NOT NULL,
    notes TEXT NULL,
    ip_address VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE RESTRICT
);