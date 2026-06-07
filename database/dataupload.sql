ALTER TABLE notifications 
MODIFY type ENUM('bid_placed', 'auction_won', 'auction_lost', 'payment_success', 'kyc_approved', 'kyc_rejected', 'withdrawal_success', 'dispute_opened', 'escrow_released', 'general') NOT NULL;
