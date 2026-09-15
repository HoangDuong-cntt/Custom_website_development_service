USE hoangduongtech_db;

-- Preserve old data before removing the legacy enum value.
UPDATE leads SET status = 'In-progress' WHERE status = 'Completed';
ALTER TABLE leads MODIFY COLUMN status ENUM('New','In-progress','Closed_Won','Cancelled') NOT NULL DEFAULT 'New';

CREATE TABLE IF NOT EXISTS contracts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lead_id INT UNSIGNED NOT NULL,
  contract_code VARCHAR(50) NOT NULL UNIQUE,
  buyer_name VARCHAR(150) NOT NULL,
  buyer_tax_id VARCHAR(50) NULL,
  buyer_address VARCHAR(255) NULL,
  final_price DECIMAL(12,2) NOT NULL,
  contract_date DATE NOT NULL,
  terms TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_hdt_contract_lead_v2026 FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Compatibility upgrade for the contracts table created in the prior version.
ALTER TABLE contracts ADD COLUMN IF NOT EXISTS contract_code VARCHAR(50) NULL UNIQUE AFTER lead_id;
ALTER TABLE contracts ADD COLUMN IF NOT EXISTS buyer_name VARCHAR(150) NULL AFTER contract_code;
ALTER TABLE contracts ADD COLUMN IF NOT EXISTS buyer_tax_id VARCHAR(50) NULL AFTER buyer_name;
ALTER TABLE contracts ADD COLUMN IF NOT EXISTS buyer_address VARCHAR(255) NULL AFTER buyer_tax_id;
ALTER TABLE contracts ADD COLUMN IF NOT EXISTS contract_date DATE NULL AFTER final_price;
ALTER TABLE contracts ADD COLUMN IF NOT EXISTS terms TEXT NULL AFTER contract_date;

-- Existing contracts retain their prior information and receive a unique code.
UPDATE contracts SET contract_code = CONCAT('HD-', YEAR(COALESCE(created_at,CURDATE())), '-', LPAD(id,3,'0')) WHERE contract_code IS NULL;
UPDATE contracts SET contract_date = COALESCE(contract_date, DATE(created_at)) WHERE contract_date IS NULL;
-- Copy legacy fields only when this update is applied to the previous contracts schema.
SET @legacy_buyer := (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='contracts' AND column_name='company_name_buyer');
SET @sql := IF(@legacy_buyer>0, "UPDATE contracts SET buyer_name=COALESCE(NULLIF(buyer_name,''),company_name_buyer,'Khách hàng') WHERE buyer_name IS NULL OR buyer_name=''", 'SELECT 1'); PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @legacy_terms := (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='contracts' AND column_name='contract_details');
SET @sql := IF(@legacy_terms>0, 'UPDATE contracts SET terms=COALESCE(terms,contract_details) WHERE terms IS NULL', 'SELECT 1'); PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @sql := IF(@legacy_buyer>0, 'ALTER TABLE contracts MODIFY company_name_buyer VARCHAR(255) NULL', 'SELECT 1'); PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @sql := IF(@legacy_terms>0, 'ALTER TABLE contracts MODIFY contract_details LONGTEXT NULL', 'SELECT 1'); PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
