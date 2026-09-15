USE hoangduongtech_db;

ALTER TABLE leads MODIFY status ENUM('New','In-progress','Completed','Cancelled','Closed_Won') NOT NULL DEFAULT 'New';

CREATE TABLE IF NOT EXISTS contracts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lead_id INT UNSIGNED NOT NULL,
  company_name_buyer VARCHAR(255) NOT NULL,
  final_price DECIMAL(12,2) NOT NULL,
  contract_details LONGTEXT NOT NULL,
  status ENUM('Draft','Signed') NOT NULL DEFAULT 'Draft',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_contract_lead FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE RESTRICT,
  INDEX idx_contract_lead (lead_id),
  INDEX idx_contract_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

UPDATE settings SET value='0586526117' WHERE key_name='hotline';
UPDATE settings SET value='https://zalo.me/0586526117' WHERE key_name='zalo';
INSERT INTO settings(key_name,value) VALUES ('office_address','[Điền địa chỉ văn phòng của bạn]'),('business_license','Mã số doanh nghiệp: 0101234567 - Sở KH&ĐT cấp ngày 10/01/2020') ON DUPLICATE KEY UPDATE value=value;
