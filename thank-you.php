<?php
/*
 * File: thank-you.php
 * Mục đích: Trang xác nhận form đã được tiếp nhận, dành cho trải nghiệm khách hàng sau khi gửi yêu cầu.
 * Phần: Public frontend / lead confirmation page.
 */
require __DIR__ . '/includes/bootstrap.php';
$title = 'Cảm ơn bạn — HoangDuongTech';
require __DIR__ . '/includes/header.php'; ?>
<main class="bg-light py-5">
    <div class="container py-lg-5">
        <div class="card border-0 shadow-sm mx-auto text-center" style="max-width:760px" data-reveal="zoom-in">
            <div class="card-body p-4 p-md-5">
                <div class="display-4 text-success mb-3"><i class="bi bi-check-circle-fill"></i></div>
                <p class="eyebrow text-primary">Yêu cầu đã được tiếp nhận</p>
                <h1 class="h2 fw-bold">Cảm ơn bạn!</h1>
                <p class="lead">Chuyên viên sẽ gọi lại từ số <button type="button" class="btn btn-link text-dark fw-bold p-0 align-baseline" data-copy-phone="0586526117">0586526117</button> trong vòng 5 phút nữa.</p>
                <div class="d-flex justify-content-center flex-wrap gap-2 mt-4"><a class="btn btn-primary btn-lg" target="_blank" href="https://zalo.me/0586526117"><i class="bi bi-chat-dots-fill me-2"></i>Chat Zalo Ngay - 0586526117</a><a class="btn btn-outline-primary btn-lg" href="index.php#mau-web">Xem Dự Án Đã Làm</a></div>
                <p class="text-secondary mt-4 mb-0">Trong lúc chờ đợi, bạn có thể tham khảo thêm các mẫu giao diện và hồ sơ năng lực của chúng tôi ở bên dưới.</p>
            </div>
        </div>
    </div>
</main><?php require __DIR__ . '/includes/footer.php'; ?>