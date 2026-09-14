CREATE DATABASE IF NOT EXISTS hoangduongtech_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hoangduongtech_db;

CREATE TABLE users (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, username VARCHAR(60) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, email VARCHAR(150) NOT NULL UNIQUE, role ENUM('admin','staff') NOT NULL DEFAULT 'staff', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB;
CREATE TABLE categories (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, slug VARCHAR(120) NOT NULL UNIQUE) ENGINE=InnoDB;
CREATE TABLE templates (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, category_id INT UNSIGNED NOT NULL, title VARCHAR(180) NOT NULL, thumbnail VARCHAR(255) DEFAULT NULL, demo_link VARCHAR(255) DEFAULT '#', price DECIMAL(12,2) NOT NULL DEFAULT 0, description TEXT, status ENUM('active','inactive') DEFAULT 'active', CONSTRAINT fk_template_category FOREIGN KEY(category_id) REFERENCES categories(id) ON DELETE CASCADE) ENGINE=InnoDB;
CREATE TABLE pricing_plans (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, plan_name VARCHAR(100) NOT NULL, price DECIMAL(12,2) NOT NULL, description TEXT, is_popular TINYINT(1) NOT NULL DEFAULT 0) ENGINE=InnoDB;
CREATE TABLE plan_features (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, plan_id INT UNSIGNED NOT NULL, feature_name VARCHAR(255) NOT NULL, is_included TINYINT(1) NOT NULL DEFAULT 1, CONSTRAINT fk_feature_plan FOREIGN KEY(plan_id) REFERENCES pricing_plans(id) ON DELETE CASCADE) ENGINE=InnoDB;
CREATE TABLE leads (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, fullname VARCHAR(150) NOT NULL, phone VARCHAR(30) NOT NULL, email VARCHAR(150) DEFAULT NULL, service_type VARCHAR(100) DEFAULT NULL, budget VARCHAR(100) DEFAULT NULL, note TEXT, status ENUM('New','In-progress','Completed','Cancelled') NOT NULL DEFAULT 'New', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, INDEX idx_leads_status(status), INDEX idx_leads_created(created_at)) ENGINE=InnoDB;
CREATE TABLE posts (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL UNIQUE, content LONGTEXT, thumbnail VARCHAR(255) DEFAULT NULL, meta_title VARCHAR(255), meta_description VARCHAR(320), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB;
CREATE TABLE settings (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, key_name VARCHAR(100) NOT NULL UNIQUE, value TEXT) ENGINE=InnoDB;

INSERT INTO users(username,password,email,role) VALUES ('admin','$2y$12$B/KdczX1wkx2zgbPIUHGhOopr.SUT5Wf9dZVRhVwvD8vtAO0dKQRK','admin@hoangduongtech.local','admin');
INSERT INTO categories(name,slug) VALUES ('Doanh nghiệp','doanh-nghiep'),('Bán hàng','ban-hang'),('Nhà hàng','nha-hang'),('Landing Page','landing-page');
INSERT INTO templates(category_id,title,thumbnail,demo_link,price,description,status) VALUES
(1,'Corporate Pro','https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80','#',5900000,'Website công ty chuyên nghiệp, tối ưu chuyển đổi.','active'),
(2,'Shop Modern','https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=800&q=80','#',7900000,'Mẫu cửa hàng trực tuyến hiện đại.','active'),
(3,'Taste Restaurant','https://images.unsplash.com/photo-1515003197210-e0cd71810b5f?auto=format&fit=crop&w=800&q=80','#',4900000,'Giới thiệu nhà hàng và thực đơn.','active'),
(4,'Launch SaaS','https://images.unsplash.com/photo-1558655146-d09347e92766?auto=format&fit=crop&w=800&q=80','#',3900000,'Landing page tập trung thu lead.','active'),
(1,'Agency Studio','https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=800&q=80','#',6900000,'Portfolio sáng tạo cho agency.','active'),
(2,'Fashion Store','https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=800&q=80','#',8900000,'E-commerce thời trang responsive.','active');
INSERT INTO pricing_plans(plan_name,price,description,is_popular) VALUES ('Starter',3900000,'Khởi đầu chuyên nghiệp',0),('Business',6900000,'Phù hợp doanh nghiệp nhỏ',1),('E-commerce',11900000,'Bán hàng trực tuyến',0),('Custom',19900000,'Giải pháp theo yêu cầu',0);
INSERT INTO plan_features(plan_id,feature_name,is_included) VALUES (1,'Giao diện responsive',1),(1,'Tối ưu SEO cơ bản',1),(1,'Hosting & domain',0),(2,'Giao diện responsive',1),(2,'Tối ưu SEO nâng cao',1),(2,'Quản trị nội dung',1),(3,'Giao diện responsive',1),(3,'Giỏ hàng & thanh toán',1),(3,'Quản lý đơn hàng',1),(4,'Thiết kế riêng 100%',1),(4,'Tích hợp hệ thống',1),(4,'Hỗ trợ ưu tiên',1);
INSERT INTO settings(key_name,value) VALUES ('site_name','HoangDuongTech'),('hotline','0901 234 567'),('zalo','https://zalo.me/0901234567'),('notification_email','hello@hoangduongtech.vn'),('logo','');
