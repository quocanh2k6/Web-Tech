SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Bảng phân quyền
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO roles (name) VALUES ('Root'), ('Staff'), ('Customer') ON DUPLICATE KEY UPDATE name=name;

-- Bảng người dùng
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar_url VARCHAR(255) DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    birth_date DATE DEFAULT NULL,
    gender VARCHAR(20) DEFAULT NULL,
    role_id INT NOT NULL DEFAULT 3,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm tài khoản Root mặc định (SĐT: root, Pass: 123)
INSERT IGNORE INTO users (full_name, phone, email, password, role_id) VALUES 
('System Administrator', 'root', 'root@technova.vn', '$2y$12$Bt1oJnAxKdGU/TakBv1gwec/i8FB2ST1y0/mQTe9fZQea3lFMktzy', 1);

-- Bảng danh mục sản phẩm
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    is_active TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO categories (name, is_active) VALUES 
('Điện thoại & Tablet', 1), 
('Laptop & Máy tính', 1), 
('Tai nghe & Âm thanh', 1), 
('Đồng hồ thông minh', 1), 
('Phụ kiện công nghệ', 1) 
ON DUPLICATE KEY UPDATE name=name;

-- Bảng sản phẩm
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(15, 2) NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm một số sản phẩm mẫu
INSERT INTO products (category_id, name, description, price, image_url) VALUES 
(1, 'iPhone 16 Pro Max 256GB', 'Thiết kế titan siêu nhẹ, chip A18 Pro mạnh mẽ, hệ thống camera 48MP sắc nét cùng màn hình Super Retina XDR 6.7 inch. Trải nghiệm đỉnh cao của công nghệ di động.', 34990000, 'assets/products/iphone16.jpg'),
(1, 'Samsung Galaxy S25 Ultra', 'Màn hình Dynamic AMOLED 2X 6.8 inch, tích hợp S Pen, chip Snapdragon 8 Gen 4, camera 200MP zoom quang học xuất sắc.', 33590000, 'assets/products/s25ultra.jpg'),
(1, 'iPad Pro M4 11 inch', 'Sức mạnh vượt trội với chip M4 mới nhất, màn hình Ultra Retina XDR tuyệt đẹp, mỏng nhẹ khó tin. Công cụ đắc lực cho sáng tạo.', 28990000, 'assets/products/ipadpro.jpg'),
(2, 'MacBook Pro 14 M4', 'Chip M4 Pro xử lý mượt mà mọi tác vụ nặng, màn hình Liquid Retina XDR độ sáng lên đến 1600 nits, thời lượng pin kinh ngạc.', 42990000, 'assets/products/macbookpro.jpg'),
(2, 'ASUS ROG Zephyrus G16', 'Cỗ máy gaming siêu mỏng nhẹ, trang bị Intel Core Ultra 9, RTX 4080, màn hình OLED 240Hz mang lại trải nghiệm đỉnh cao.', 65990000, 'assets/products/zephyrus.jpg'),
(2, 'Dell XPS 14 2026', 'Thiết kế sang trọng, nhôm nguyên khối, viền màn hình siêu mỏng, chip Intel thế hệ mới nhất cho hiệu năng văn phòng hoàn hảo.', 38500000, 'assets/products/xps14.jpg'),
(3, 'Sony WH-1000XM5', 'Tai nghe chống ồn chủ động tốt nhất thế giới, âm thanh High-Resolution Audio, thiết kế thoải mái, pin lên đến 30 giờ.', 8490000, 'assets/products/sonyxm5.jpg'),
(3, 'AirPods Pro 2', 'Tai nghe True Wireless với khả năng khử tiếng ồn chủ động xuất sắc, âm thanh không gian cá nhân hóa và hộp sạc MagSafe.', 6190000, 'assets/products/airpodspro.jpg'),
(3, 'JBL Charge 6', 'Loa bluetooth di động công suất lớn, âm thanh Bass mạnh mẽ, chống nước IP67, kiêm sạc dự phòng cho điện thoại.', 3990000, 'assets/products/jblcharge.jpg'),
(4, 'Apple Watch Series 10', 'Màn hình lớn hơn, viền siêu mỏng, theo dõi sức khỏe toàn diện với nhiều cảm biến mới, sạc nhanh và chống nước.', 10990000, 'assets/products/applewatch.jpg'),
(4, 'Samsung Galaxy Watch 7', 'Thiết kế cổ điển kết hợp công nghệ hiện đại, theo dõi nhịp tim, giấc ngủ chính xác, mặt kính Sapphire bền bỉ.', 7490000, 'assets/products/galaxywatch.jpg'),
(4, 'Garmin Fenix 8', 'Đồng hồ GPS thể thao cao cấp, vỏ titan, thời lượng pin bằng năng lượng mặt trời, bản đồ điều hướng chi tiết.', 22990000, 'assets/products/garmin.jpg'),
(5, 'Sạc dự phòng Anker Prime 27,650mAh', 'Công suất 250W sạc cực nhanh cho cả laptop và điện thoại, màn hình thông minh hiển thị dung lượng pin.', 4590000, 'assets/products/anker.jpg'),
(5, 'Chuột Logitech MX Master 3S', 'Chuột không dây công thái học tốt nhất cho công việc, cảm biến 8000 DPI, cuộn MagSpeed siêu tốc và êm ái.', 2690000, 'assets/products/mxmaster.jpg'),
(5, 'Bàn phím cơ Keychron Q1 Pro', 'Bàn phím cơ không dây layout 75%, vỏ nhôm nguyên khối, mạch gasket mount cho cảm giác gõ tuyệt vời.', 4990000, 'assets/products/keychron.jpg');

-- Bảng đơn hàng
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL, -- Cho phép NULL để giữ lại hóa đơn khi user bị xóa
    coupon_id INT DEFAULT NULL, -- Thêm liên kết với bảng mã giảm giá
    total_amount DECIMAL(15, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL, -- Xóa user -> user_id = NULL
    FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON DELETE SET NULL -- Xóa mã -> coupon_id = NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng chi tiết đơn hàng 
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NULL, -- Cho phép NULL để giữ lại chi tiết hóa đơn khi sản phẩm ngừng kinh doanh
    quantity INT NOT NULL,
    price DECIMAL(15, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE, -- Đơn hàng bị xóa thì chi tiết bị xóa theo (hợp lý)
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL -- Xóa sản phẩm -> product_id = NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng liên hệ
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng đăng ký nhận bản tin
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng lưu vết quản trị viên (Admin Logs)
CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng mã giảm giá (Mới)
CREATE TABLE IF NOT EXISTS coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount_type ENUM('percent', 'fixed') DEFAULT 'percent',
    discount_value DECIMAL(15, 2) NOT NULL,
    usage_limit INT DEFAULT NULL,
    used_count INT DEFAULT 0,
    start_date DATETIME NULL,
    end_date DATETIME NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng cài đặt website (Mới)
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_name VARCHAR(255) NOT NULL DEFAULT 'TechNova Store',
    logo_url VARCHAR(255) NULL,
    phone VARCHAR(50) NULL,
    email VARCHAR(100) NULL,
    address TEXT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO settings (id, site_name) VALUES (1, 'TechNova Store');
