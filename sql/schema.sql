-- Database schema for BenTech Collaborations
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    short_description TEXT NOT NULL,
    full_description TEXT NOT NULL,
    base_price DECIMAL(12,2) NOT NULL,
    currency VARCHAR(10) NOT NULL DEFAULT 'UGX',
    duration_minutes INT NOT NULL,
    allow_deposit TINYINT(1) NOT NULL DEFAULT 0,
    deposit_percentage DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    delivery_time_text VARCHAR(255) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category_id),
    CONSTRAINT fk_packages_category FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_code VARCHAR(50) NOT NULL UNIQUE,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    company_name VARCHAR(255) NULL,
    website_url VARCHAR(255) NULL,
    brief TEXT NOT NULL,
    preferred_timeline VARCHAR(255) NULL,
    payment_type ENUM('full','deposit') NOT NULL,
    total_amount DECIMAL(12,2) NOT NULL,
    amount_due_now DECIMAL(12,2) NOT NULL,
    currency VARCHAR(10) NOT NULL DEFAULT 'UGX',
    status ENUM('pending_payment','deposit_paid','paid_full','cancelled') NOT NULL DEFAULT 'pending_payment',
    pesapal_merchant_reference VARCHAR(255) NULL,
    pesapal_transaction_tracking_id VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    package_id INT NOT NULL,
    package_name_snapshot VARCHAR(255) NOT NULL,
    unit_price DECIMAL(12,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    line_total DECIMAL(12,2) NOT NULL,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_order_items_package FOREIGN KEY (package_id) REFERENCES packages(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    currency VARCHAR(10) NOT NULL,
    payment_type ENUM('deposit','balance','full') NOT NULL,
    provider VARCHAR(50) NOT NULL DEFAULT 'pesapal',
    status ENUM('successful','failed','pending') NOT NULL DEFAULT 'pending',
    pesapal_transaction_tracking_id VARCHAR(255) NULL,
    pesapal_payment_method VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS portfolio_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    brand_name VARCHAR(255) NOT NULL,
    youtube_url VARCHAR(255) NOT NULL,
    collab_type VARCHAR(255) NOT NULL,
    short_description TEXT NOT NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data
INSERT INTO categories (name, slug, description, is_active, created_at, updated_at) VALUES
('YouTube Video', 'youtube-video', 'Video collaborations on BenTech YouTube channel.', 1, NOW(), NOW()),
('Combo Packages', 'combo-packages', 'Bundled collaboration offers that combine deliverables.', 1, NOW(), NOW());

INSERT INTO packages (category_id, name, slug, short_description, full_description, base_price, currency, duration_minutes, allow_deposit, deposit_percentage, delivery_time_text, is_active, created_at, updated_at) VALUES
(1, 'Dedicated Review Video (4-8 mins)', 'dedicated-review-video', 'Full video reviewing the product/service with honest insights.', 'A deep-dive review on the BenTech channel covering setup, experience, pros/cons, and recommendations.', 250000.00, 'UGX', 8, 1, 50.00, '7-10 business days after brief and payment', 1, NOW(), NOW()),
(1, 'Feature Segment (2-4 mins)', 'feature-segment', 'Feature block inside a relevant video with CTA.', 'We integrate your brand as a segment inside an upcoming video, including CTA and link.', 150000.00, 'UGX', 4, 1, 50.00, '5-7 business days', 1, NOW(), NOW()),
(1, 'Quick Mention / Shoutout (30-60s)', 'quick-mention-shoutout', 'Short shoutout with link and lower-third mention.', 'Concise mention for brand awareness, including a link and overlay.', 80000.00, 'UGX', 1, 0, 0.00, '3-5 business days', 1, NOW(), NOW()),
(1, 'Step-by-Step Tutorial / Walkthrough', 'step-by-step-tutorial', 'Guided tutorial showcasing your product in action.', 'Recorded walkthrough showing setup, core workflows, and best practices for your product.', 180000.00, 'UGX', 12, 1, 50.00, '7-10 business days', 1, NOW(), NOW()),
(1, 'Explainer Video (screen recording + voiceover)', 'explainer-video', 'Clean explainer with screen captures and narration.', 'Scripted explainer crafted with screen recordings, captions, and voiceover.', 200000.00, 'UGX', 10, 1, 50.00, '7-10 business days', 1, NOW(), NOW()),
(2, 'Starter Combo (Review + Mention)', 'starter-combo-review-mention', 'A review video plus a quick shoutout for extra awareness.', 'This combo includes one detailed review and one short mention in a second video to boost repeat visibility.', 300000.00, 'UGX', 10, 1, 50.00, '7-12 business days', 1, NOW(), NOW()),
(2, 'Launch Combo (Tutorial + Explainer)', 'launch-combo-tutorial-explainer', 'Pair a hands-on tutorial with a focused explainer.', 'Ideal for product launches where you need both a practical walkthrough and a concise explainer asset.', 360000.00, 'UGX', 14, 1, 50.00, '8-14 business days', 1, NOW(), NOW());

INSERT INTO portfolio_items (title, brand_name, youtube_url, collab_type, short_description, is_featured, created_at, updated_at) VALUES
('SmartHome Hub Review', 'CasaTech', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'Dedicated Review', 'Full breakdown of smart home hub with real-life automations.', 1, NOW(), NOW()),
('Productivity App Walkthrough', 'FlowSuite', 'https://www.youtube.com/watch?v=oHg5SJYRHA0', 'Tutorial', 'Step-by-step of onboarding and core workflows.', 1, NOW(), NOW()),
('Audio Gear Spotlight', 'SoundForge', 'https://www.youtube.com/watch?v=3GwjfUFyY6M', 'Feature Segment', 'Feature in a creator tools roundup.', 0, NOW(), NOW());
