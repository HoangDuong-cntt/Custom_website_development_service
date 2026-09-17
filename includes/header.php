<?php
/*
 * File: includes/header.php
 * Mục đích: Render phần đầu trang, favicon, logo và navbar chung của website.
 * Phần: Frontend layout / shared header.
 */
$site = settings(); ?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e($title ?? 'HoangDuongTech — Thiết kế website') ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= e(SITE_LOGO_FILE) ?>">
    <link rel="apple-touch-icon" href="<?= e(SITE_LOGO_FILE) ?>">
    <script>
        // Áp dụng theme đã lưu trước khi UI render để tránh nháy màu và flash layout.
        (() => {
            const savedTheme = localStorage.getItem('hdt-theme');
            const theme = savedTheme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.dataset.theme = theme;
            document.documentElement.classList.toggle('dark', theme === 'dark');
            window.hdtApplyTheme = (nextTheme) => {
                const apply = () => {
                    document.documentElement.dataset.theme = nextTheme;
                    document.documentElement.classList.toggle('dark', nextTheme === 'dark');
                };
                if (document.startViewTransition) document.startViewTransition(apply);
                else apply();
                localStorage.setItem('hdt-theme', nextTheme);
                document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
                    const dark = nextTheme === 'dark';
                    button.setAttribute('aria-pressed', dark ? 'true' : 'false');
                    button.setAttribute('aria-label', dark ? 'Bật chế độ sáng' : 'Bật chế độ tối');
                });
            };
            window.hdtToggleTheme = () => window.hdtApplyTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark');
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
    <link href="assets/scroll-effects.css" rel="stylesheet">
    <link href="assets/interactive-ui.css" rel="stylesheet">
    <link href="assets/pricing-effects.css" rel="stylesheet">
    <link href="assets/css/3d-effects.css" rel="stylesheet">
    <link href="assets/css/theme.css" rel="stylesheet">
    <script src="assets/js/main-animation.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.161.0/build/three.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/lenis@1.1.20/dist/lenis.min.js" defer></script>
    <script src="assets/js/scroll-effects.js" defer></script>
    <script src="assets/js/3d-motion.js" defer></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white sticky-top border-bottom">
        <div class="container"><a class="navbar-brand brand-logo" href="index.php" aria-label="DHDTECH - Trang chủ"><img src="<?= e(SITE_LOGO_FILE) ?>" alt="DHDTECH" class="site-logo site-logo-header"></a>
            <div class="d-flex align-items-center gap-2 order-lg-last"><button type="button" class="theme-toggle" data-theme-toggle onclick="window.hdtToggleTheme()" aria-label="Bật chế độ tối" aria-pressed="false"><i class="bi bi-sun-fill theme-icon theme-icon-sun" aria-hidden="true"></i><i class="bi bi-moon-stars-fill theme-icon theme-icon-moon" aria-hidden="true"></i><span class="visually-hidden">Chuyển đổi giao diện sáng tối</span></button><button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button></div>
            <div id="nav" class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li><a class="nav-link" href="#mau-web">Mẫu web</a></li>
                    <li><a class="nav-link" href="#bang-gia">Bảng giá</a></li>
                    <li><a class="nav-link" href="#quy-trinh">Quy trình</a></li>
                    <li><a class="nav-link fw-semibold text-primary" href="tel:0586526117"><i class="bi bi-telephone-fill me-1"></i>0586 526 117</a></li>
                    <li><a class="btn btn-primary ms-lg-2" href="#dang-ky">Nhận báo giá</a></li>
                </ul>
            </div>
        </div>
    </nav>