# TechNova Store — Cửa Hàng Công Nghệ Cao Cấp

**TechNova Store** là một dự án đồ án website thương mại điện tử chuyên cung cấp các sản phẩm công nghệ cao cấp — từ điện thoại, laptop, tai nghe đến đồng hồ thông minh và phụ kiện. Website được phát triển trên nền tảng **PHP thuần (Vanilla PHP), MySQL, HTML/CSS (Tailwind CSS CDN) và JavaScript**.

---

## 🌟 Tính Năng Nổi Bật

### 1. Dành cho Khách hàng (Frontend)
- **Giao diện Dark Mode Premium:** Thiết kế tối giản, sang trọng theo phong cách luxury-tech với tông màu đen – vàng gold, cùng typography Be Vietnam Pro & Inter.
- **Responsive Design:** Tương thích hoàn toàn trên Desktop, Tablet và Mobile, kèm sidebar menu cho thiết bị nhỏ.
- **Trải nghiệm mua sắm:** Xem danh sách sản phẩm, chi tiết sản phẩm, lọc theo danh mục (5 danh mục), thêm vào giỏ hàng, áp mã giảm giá và đặt hàng.
- **Tìm kiếm Realtime (Live Search):** Tích hợp công cụ tìm kiếm tức thì bằng AJAX với thiết kế Dropdown mượt mà, hỗ trợ Debouncing giúp tối ưu hiệu suất server.
- **Hiệu ứng hiện đại:** Loading screen, AOS scroll animations, Swiper slider sản phẩm, parallax banner, marquee chạy chữ.
- **Quản lý Tài khoản:** Đăng ký, Đăng nhập, Quên mật khẩu, xem lịch sử đơn hàng, cập nhật hồ sơ và ảnh đại diện.
- **AI Chatbot Tích hợp:** Trợ lý ảo thông minh tư vấn khách hàng trực tiếp qua **Google Gemini API**, được tối ưu Hitbox để dễ dàng tương tác.
- **Đăng ký nhận bản tin (Newsletter):** Cho phép khách hàng đăng ký nhận email ưu đãi, hỗ trợ hủy đăng ký.

### 2. Dành cho Quản trị viên (Admin Panel)
- **Dashboard hiện đại:** Giao diện quản trị theo phong cách enterprise-grade (Stripe / Vercel), sử dụng Tailwind CSS.
- **Hệ thống Phân quyền Chặt chẽ:** 3 cấp độ — `Root` (Cao nhất), `Staff` (Nhân viên), và `Customer` (Khách hàng).
- **Quản lý Đơn hàng:** Xem, duyệt, hủy và cập nhật trạng thái đơn hàng. Xem chi tiết đơn hàng.
- **Quản lý Người dùng:** Thêm, sửa, xóa tài khoản. Staff không có quyền xóa các tài khoản Root hoặc Staff khác.
- **Quản lý Sản phẩm & Danh mục:** Thêm, sửa, xóa sản phẩm và danh mục một cách trực quan.
- **Quản lý Mã giảm giá (Coupons):** Tạo mã giảm giá theo phần trăm hoặc giá trị cố định, giới hạn lượt sử dụng và thời gian hiệu lực.
- **Quản lý Newsletter:** Xem danh sách người đăng ký, gửi email thông báo hàng loạt qua PHPMailer.
- **Cài đặt Website (Settings):** Cấu hình tên cửa hàng, logo, thông tin liên hệ trực tiếp từ Admin.
- **Nhật ký Hoạt động (Admin Logs):** Ghi lại mọi thao tác quản trị để giám sát và truy vết.

### 3. Bảo mật (Security)
- **Mã hóa Mật khẩu:** Tất cả mật khẩu đều được băm bằng thuật toán `Bcrypt` (`password_hash` của PHP).
- **Chống SQL Injection:** 100% truy vấn CSDL đều sử dụng `PDO Prepared Statements`.
- **Chống Đánh cắp Dữ liệu:** Tích hợp file `.htaccess` chặn truy cập trực tiếp vào các file cấu hình (`.sql`, `.env`, `.json`) và chặn Directory Listing.
- **Bảo vệ Giao diện:** JavaScript vô hiệu hóa phím tắt F12, Ctrl+U, Click chuột phải để hạn chế soi mã nguồn.

---

## 🛠️ Công Nghệ Sử Dụng

| Thành phần        | Công nghệ                                             |
|-------------------|-------------------------------------------------------|
| **Backend**       | PHP 8.x (Vanilla PHP), PDO                            |
| **Database**      | MySQL / MariaDB                                       |
| **Frontend**      | HTML5, CSS3, JavaScript (ES6+)                        |
| **CSS Framework** | Tailwind CSS (CDN)                                    |
| **Fonts**         | Be Vietnam Pro, Inter (Google Fonts)                  |
| **Icons**         | Font Awesome 6                                        |
| **Thư viện JS**   | Swiper.js (slider), AOS (scroll animation)            |
| **AI Chatbot**    | Google Gemini API                                     |
| **Email**         | PHPMailer 6.9+ (Composer)                             |

---

## 📂 Cấu Trúc Dự Án

```
Technova-main/
├── admin/                    # Trang quản trị
│   ├── includes/             # Header, sidebar, footer admin
│   ├── index.php             # Dashboard chính
│   ├── products.php          # Quản lý sản phẩm
│   ├── product_form.php      # Form thêm/sửa sản phẩm
│   ├── categories.php        # Quản lý danh mục
│   ├── category_form.php     # Form thêm/sửa danh mục
│   ├── orders.php            # Quản lý đơn hàng
│   ├── order_detail.php      # Chi tiết đơn hàng
│   ├── users.php             # Quản lý người dùng
│   ├── user_form.php         # Form thêm/sửa người dùng
│   ├── user_detail.php       # Chi tiết người dùng
│   ├── coupons.php           # Quản lý mã giảm giá
│   ├── coupon_form.php       # Form thêm/sửa mã giảm giá
│   ├── newsletter.php        # Quản lý newsletter
│   ├── settings.php          # Cài đặt website
│   └── logs.php              # Nhật ký hoạt động
├── ajax/                     # API endpoints (AJAX)
│   ├── add_to_cart.php
│   ├── get_cart_count.php
│   ├── process_checkout.php
│   ├── process_contact.php
│   ├── search_products.php
│   ├── subscribe_newsletter.php
│   └── chatbot_api.php       # API Chatbot (Gemini)
├── assets/                   # Tài nguyên tĩnh
│   ├── avatars/              # Ảnh đại diện người dùng
│   └── images/               # Hình ảnh chung (QR code, etc.)
├── config/                   # Cấu hình
│   ├── db_connect.php        # Kết nối CSDL
│   ├── database.sql          # Schema CSDL
│   └── newsletter_mail.php   # Cấu hình email SMTP
├── css/
│   └── styles.css            # CSS tùy chỉnh
├── includes/                 # Components dùng chung
│   ├── header.php            # Header + Navigation
│   ├── footer.php            # Footer
│   ├── helpers.php           # Hàm tiện ích
│   └── newsletter_mailer.php # Xử lý gửi email
├── js/
│   └── script.js             # Script chính của website
├── index.php                 # Trang chủ
├── shop.php                  # Trang cửa hàng
├── product_detail.php        # Chi tiết sản phẩm
├── cart.php                  # Giỏ hàng
├── checkout.php              # Thanh toán
├── auth.php                  # Đăng nhập / Đăng ký
├── forgot_password.php       # Quên mật khẩu
├── profile.php               # Hồ sơ cá nhân
├── orders.php                # Lịch sử đơn hàng
├── about.php                 # Giới thiệu
├── careers.php               # Tuyển dụng
├── support.php               # Liên hệ / Hỗ trợ
├── .htaccess                 # Cấu hình bảo mật Apache
├── composer.json             # Quản lý thư viện PHP
└── README.md                 # Tài liệu dự án
```

---

## 🚀 Hướng Dẫn Cài Đặt

### 1. Yêu cầu Hệ thống
- **PHP** >= 8.0
- **MySQL** hoặc **MariaDB**
- **Composer** (để cài PHPMailer)
- **XAMPP** / MAMP / WAMP (nếu chạy trên Localhost)

### 2. Các bước triển khai

**Bước 1: Clone dự án**
```bash
git clone https://github.com/quocanh2k6/Web-Tech.git
```
Chuyển toàn bộ mã nguồn vào thư mục `htdocs` (nếu dùng XAMPP).

**Bước 2: Cài đặt thư viện PHP**
```bash
composer install
```
Lệnh này sẽ cài đặt **PHPMailer** — thư viện gửi email cho tính năng Newsletter.

**Bước 3: Cài đặt Database**
1. Mở **phpMyAdmin**.
2. Import file `database.sql` đi kèm trong thư mục gốc.
3. Hệ thống sẽ tự động tạo các bảng: `roles`, `users`, `categories`, `products`, `orders`, `order_items`, `contacts`, `newsletter_subscribers`, `admin_logs`, `coupons`, `settings` cùng dữ liệu mẫu (15 sản phẩm công nghệ).
4. **Lưu ý:** Trước khi import, hãy tạo database tên `technova_db` trong phpMyAdmin (hoặc chỉnh sửa file `config/db_connect.php` theo tên database bạn muốn).

**Tài khoản quản trị mặc định:**
| Trường    | Giá trị |
|-----------|---------|
| Tài khoản | `root`  |
| Mật khẩu  | `123`   |

**Bước 4: Cấu hình kết nối Database**

Mở file `config/db_connect.php` và thay đổi các thông số cho phù hợp với môi trường của bạn:
```php
$host     = 'localhost';
$db_name  = 'technova_db';
$username = 'root';
$password = '';
```
> Nếu chạy local XAMPP thì thường không cần thay đổi gì.

**Bước 5: Cấu hình API Key (AI Chatbot)**

Để Chatbot hoạt động, bạn cần cung cấp một khóa API của Google Gemini:
1. Mở file `ajax/chatbot_api.php`.
2. Tìm biến `$api_key = "YOUR_GEMINI_API_KEY_HERE";`.
3. Thay thế bằng API Key thật của bạn (nhận miễn phí tại [Google AI Studio](https://aistudio.google.com/)).

**Bước 6: Cấu hình Email (Newsletter — Tùy chọn)**
1. Mở file `config/newsletter_mail.php`.
2. Thay thế thông tin SMTP (email & App Password Gmail) bằng thông tin thực tế của bạn.

**Bước 7: Khởi chạy**
1. Mở **XAMPP Control Panel**, start **Apache** và **MySQL**.
2. Truy cập: `http://localhost/Technova-main/`

---

## 🗄️ Cơ Sở Dữ Liệu

Database sử dụng **10 bảng** chính:

| Bảng                       | Mô tả                          |
|----------------------------|---------------------------------|
| `roles`                    | Phân quyền (Root, Staff, Customer) |
| `users`                    | Người dùng                      |
| `categories`               | Danh mục sản phẩm              |
| `products`                 | Sản phẩm                       |
| `orders`                   | Đơn hàng                       |
| `order_items`              | Chi tiết đơn hàng              |
| `contacts`                 | Liên hệ từ khách hàng          |
| `newsletter_subscribers`   | Đăng ký nhận bản tin           |
| `admin_logs`               | Nhật ký hoạt động quản trị     |
| `coupons`                  | Mã giảm giá                    |
| `settings`                 | Cài đặt website                |

**5 danh mục sản phẩm mặc định:** Điện thoại & Tablet, Laptop & Máy tính, Tai nghe & Âm thanh, Đồng hồ thông minh, Phụ kiện công nghệ.


---

## 🎨 Tác Giả

Dự án được xây dựng phục vụ cho đồ án môn học **Phát triển Ứng dụng Web**.

Mọi đóng góp, báo lỗi vui lòng tạo [Issues](https://github.com/quocanh2k6/Web-Tech/issues) trong repository.

---

<p align="center">
  <b>TechNova Store</b> — Công Nghệ Đỉnh Cao 
</p>
