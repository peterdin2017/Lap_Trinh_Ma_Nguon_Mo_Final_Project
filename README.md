<div align="center">

# 🛍️ Website Bán hàng Online

**Hướng dẫn cài đặt, chạy thử và vận hành dự án**

Laravel · PHP 8.3+ · Blade · Vite · SQLite

</div>

---

## Giới thiệu

Dự án là website thương mại điện tử gồm ba khu vực cùng chạy trong một ứng dụng Laravel:

| Khu vực | Đường dẫn | Dành cho |
|---|---|---|
| Store | `/` | Khách hàng mua sắm |
| Seller | `/seller/login` | Người bán quản lý cửa hàng |
| Admin | `/admin/login` | Quản trị viên vận hành sàn |

## Chức năng

### Store · Khách hàng

- Đăng ký, xác minh OTP, đăng nhập và khôi phục mật khẩu.
- Tìm kiếm, lọc và xem chi tiết sản phẩm, cửa hàng.
- Quản lý giỏ hàng, địa chỉ nhận hàng, voucher và đặt hàng.
- Theo dõi đơn, xác nhận đã nhận hàng, gửi yêu cầu trả hàng/hoàn tiền.
- Quản lý hồ sơ, đổi mật khẩu, chat với cửa hàng.
- Đánh giá sản phẩm sau khi đơn hoàn tất và gửi báo cáo vi phạm.

### Seller · Người bán

- Đăng ký, gửi hồ sơ cửa hàng và theo dõi trạng thái xét duyệt.
- Quản lý sản phẩm, hình ảnh, biến thể, tồn kho và trạng thái hiển thị.
- Quản lý đơn hàng, cập nhật trạng thái, in phiếu giao hàng.
- Xử lý yêu cầu trả hàng/hoàn tiền, tạo voucher và xem báo cáo doanh thu.
- Cập nhật thông tin cửa hàng, đổi mật khẩu và chat với khách hàng.

### Admin · Quản trị viên

- Theo dõi dashboard và thống kê hoạt động toàn sàn.
- Duyệt hồ sơ Seller; quản lý tài khoản và danh mục.
- Kiểm duyệt sản phẩm, quản lý voucher toàn sàn.
- Tiếp nhận báo cáo vi phạm và xuất báo cáo thống kê.

## Yêu cầu môi trường

| Thành phần | Yêu cầu |
|---|---|
| PHP | 8.3 trở lên |
| Composer | Phiên bản hiện hành |
| Node.js và npm | Cần để cài và biên dịch tài nguyên Vite |
| Cơ sở dữ liệu | SQLite theo cấu hình mẫu; PHP cần bật `pdo_sqlite` |
| Trình duyệt | Chrome, Edge hoặc trình duyệt hiện đại tương đương |

Kiểm tra công cụ đã sẵn sàng:

```bash
php -v
composer --version
node -v
npm -v
```

## Cài đặt trên Windows

Mở PowerShell hoặc Command Prompt tại thư mục gốc dự án, nơi có `artisan`, `composer.json` và `package.json`.

### 1. Cài dependencies

```bash
composer install
npm install
```

### 2. Tạo cấu hình môi trường

PowerShell:

```powershell
Copy-Item .env.example .env
```

Command Prompt:

```cmd
copy .env.example .env
```

Tạo khóa ứng dụng:

```bash
php artisan key:generate
```

### 3. Cấu hình SQLite

File mẫu dùng SQLite. Kiểm tra `.env` có cấu hình phù hợp:

```env
DB_CONNECTION=sqlite
```

Nếu `database/database.sqlite` chưa tồn tại, tạo file:

```powershell
New-Item -ItemType File -Path database/database.sqlite -Force
```

Tạo các bảng và nạp dữ liệu demo:

```bash
php artisan migrate --seed
```

Seeder tạo tài khoản, cửa hàng, danh mục, sản phẩm, đơn hàng và báo cáo mẫu để dùng khi chạy thử.

### 4. Tạo liên kết file tải lên

```bash
php artisan storage:link
```

### 5. Build giao diện

ZIP không kèm `node_modules` hoặc file build Vite. Build tài nguyên trước khi chạy website:

```bash
npm run build
```

Khi đang phát triển giao diện, chạy Vite ở một terminal riêng:

```bash
npm run dev
```

## Khởi động và truy cập

Chạy Laravel tại thư mục gốc dự án:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Mở trình duyệt tại **http://127.0.0.1:8000**.

- Store: <http://127.0.0.1:8000/>
- Đăng nhập Seller: <http://127.0.0.1:8000/seller/login>
- Đăng nhập Admin: <http://127.0.0.1:8000/admin/login>

> **Lưu ý về đăng nhập:** dùng thống nhất `127.0.0.1` cho cả ba khu vực. Trình duyệt lưu cookie riêng cho `localhost` và `127.0.0.1`, nên đổi qua lại giữa hai địa chỉ có thể làm trạng thái đăng nhập trông không nhất quán.

Dừng Laravel hoặc Vite bằng `Ctrl + C` trong terminal tương ứng.

## Tài khoản demo

Các tài khoản dưới đây được tạo bởi `DatabaseSeeder`, `StoreCatalogSeeder` và `AdminDemoSeeder`:

| Vai trò / trạng thái | Email | Mật khẩu |
|---|---|---|
| Admin hoạt động | `admin.demo@example.com` | `AdminDemo@2026!` |
| Seller hoạt động | `demo.seller@example.com` | `Seller@123456` |
| Seller chờ duyệt | `pending.seller@example.com` | `Seller@123456` |
| Seller chờ duyệt | `pending.two@example.com` | `Seller@123456` |
| Seller chờ duyệt | `pending.three@example.com` | `Seller@123456` |
| Seller bị từ chối | `rejected.demo@example.com` | `Seller@123456` |
| Seller tạm đình chỉ | `suspended.demo@example.com` | `Seller@123456` |
| Khách hàng hoạt động | `demo.customer1@example.com` – `demo.customer6@example.com` | `Customer@123456` |

**Chỉ sử dụng tài khoản mẫu ở môi trường local/demo.** `firstOrCreate` không thay mật khẩu của bản ghi đã tồn tại; nếu tài khoản mẫu đã có trong database, mật khẩu lưu trong database có thể khác bảng trên.

## Email OTP

Cấu hình mẫu đặt `MAIL_MAILER=log`, vì vậy email OTP không được gửi ra ngoài. Trong môi trường local, xem nội dung email tại:

```text
storage/logs/laravel.log
```

Để gửi email thật, cấu hình các biến `MAIL_*` trong `.env` theo nhà cung cấp SMTP rồi khởi động lại ứng dụng.

## Lệnh vận hành thường dùng

Xóa cache cấu hình, route và view sau khi thay đổi:

```bash
php artisan optimize:clear
```

Chạy các migration mới:

```bash
php artisan migrate
```

Nạp lại seeder:

```bash
php artisan db:seed
```

Chạy bộ kiểm thử:

```bash
php artisan test
```

> `php artisan migrate:fresh --seed` sẽ xóa toàn bộ bảng và dữ liệu hiện có trước khi tạo lại. Chỉ dùng với database demo có thể xóa.

## Khắc phục nhanh

| Hiện tượng | Cách kiểm tra |
|---|---|
| Không đăng nhập được tài khoản demo | Đã chạy `php artisan migrate --seed` chưa? Tài khoản mẫu có thể đã tồn tại với mật khẩu cũ do `firstOrCreate`. |
| Trang báo thiếu manifest/Vite | Chạy `npm install` rồi `npm run build`, hoặc khởi động `npm run dev`. |
| Ảnh tải lên không hiển thị | Chạy `php artisan storage:link`. |
| OTP không tới email | Với `MAIL_MAILER=log`, kiểm tra `storage/logs/laravel.log`; muốn gửi thật cần cấu hình SMTP. |
| Đăng nhập bị mất khi đổi địa chỉ | Dùng thống nhất `127.0.0.1:8000` hoặc `localhost:8000`, không đổi qua lại. |

## Lưu ý bảo mật khi triển khai

- Không commit `.env`, mật khẩu, khóa ứng dụng hoặc thông tin kết nối thật lên GitHub.
- Không triển khai tài khoản và mật khẩu demo lên máy chủ công khai.
- Trên môi trường production, đặt `APP_DEBUG=false`, bật HTTPS và cấu hình database, mail, session phù hợp.
- Thay hoặc xóa dữ liệu tài khoản demo trước khi công khai hệ thống.
