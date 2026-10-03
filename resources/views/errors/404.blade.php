<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy trang</title>

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa; /* Màu nền xám nhạt */
        }
        .error-container {
            max-width: 600px;
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        }
        .error-code {
            font-size: 8rem;
            font-weight: 800;
            color: #0d6efd; /* Màu primary của Bootstrap */
            text-shadow: 2px 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="vh-100 d-flex align-items-center justify-content-center">

    <div class="container text-center">
        <div class="error-container p-5 mx-auto border">
            <!-- Icon -->
            <div class="mb-4">
                <i class="bi bi-emoji-frown text-warning" style="font-size: 5rem;"></i>
            </div>

            <!-- Tiêu đề lỗi -->
            <h1 class="error-code mb-0">404</h1>
            <h2 class="h3 fw-bold text-dark mb-3">Ôi không! Không tìm thấy trang.</h2>

            <!-- Mô tả -->
            <p class="text-muted mb-4 fs-5">
                Đường dẫn bạn đang cố gắng truy cập có thể đã bị xóa, thay đổi tên, hoặc tạm thời không hoạt động. Hãy kiểm tra lại nhé!
            </p>

            <!-- Nút quay về -->
            <a href="{{ url('/') }}" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm">
                <i class="bi bi-house-door-fill me-2"></i> Quay về Trang chủ
            </a>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle (Tùy chọn, cần thiết nếu có dùng dropdown/modal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
