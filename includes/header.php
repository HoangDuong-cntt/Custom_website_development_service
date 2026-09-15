<?php $site = settings(); ?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e($title ?? 'HoangDuongTech — Thiết kế website') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
    <link href="assets/scroll-effects.css" rel="stylesheet">
    <link href="assets/interactive-ui.css" rel="stylesheet">
    <link href="assets/pricing-effects.css" rel="stylesheet">
    <script src="assets/js/main-animation.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/lenis@1.1.20/dist/lenis.min.js" defer></script>
    <script src="assets/js/scroll-effects.js" defer></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white sticky-top border-bottom">
        <div class="container"><a class="navbar-brand fw-bold text-primary" href="index.php"><i class="bi bi-code-square me-2"></i><?= e($site['site_name'] ?? 'HoangDuongTech') ?></a><button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
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