<?php
require_once __DIR__ . '/auth.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT c.*, l.fullname, l.phone, l.email, l.service_type FROM contracts c JOIN leads l ON l.id = c.lead_id WHERE c.id = ?');
$stmt->execute([$id]);
$contract = $stmt->fetch();
if (!$contract) {
    http_response_code(404);
    exit('Không tìm thấy hợp đồng.');
}
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="../<?= e(SITE_LOGO_FILE) ?>">
    <title><?= e($contract['contract_code']) ?></title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            color: #111;
            line-height: 1.55;
            margin: 0;
        }

        .actions {
            padding: 16px;
            text-align: center;
            background: #f1f5f9;
        }

        .btn {
            padding: 10px 16px;
            border: 0;
            border-radius: 5px;
            background: #155eef;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            font: 14px Arial;
        }

        .paper {
            max-width: 800px;
            margin: 30px auto;
            padding: 35px 55px;
        }

        .contract-head {
            display: flex;
            align-items: flex-start;
            gap: 22px;
        }

        .contract-logo {
            width: auto;
            height: 1.35cm;
            object-fit: contain;
        }

        .contract-title {
            flex: 1;
            text-align: center;
        }

        .signature {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 70px;
            text-align: center;
            margin-top: 55px;
            min-height: 150px;
        }

        @media print {
            .actions {
                display: none;
            }

            .paper {
                max-width: none;
                margin: 0;
                padding: 18mm;
            }

            body {
                font-size: 12pt;
            }
        }
    </style>
</head>

<body>
    <div class="actions">
        <a class="btn" href="leads.php">Quay lại</a>
        <button class="btn" onclick="window.print()">In / Save PDF Hợp Đồng</button>
    </div>
    <article class="paper">
        <div class="contract-head">
            <img class="contract-logo" src="../<?= e(SITE_LOGO_FILE) ?>" alt="HDTECH">
            <div class="contract-title">
                <strong>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</strong><br>
                <strong>Độc lập - Tự do - Hạnh phúc</strong>
                <hr style="width:190px">
                <h2>HỢP ĐỒNG DỊCH VỤ</h2>
                <p>Số: <?= e($contract['contract_code']) ?></p>
            </div>
        </div>
        <p>Hôm nay, ngày <?= date('d/m/Y', strtotime($contract['contract_date'])) ?>, các bên gồm:</p>
        <p><strong>BÊN A (BÊN BÁN): HOANG DUONG TECH</strong><br>Đại diện: HOÀNG DƯƠNG<br>Hotline/Zalo: 0586526117<br>Dịch vụ: Thiết kế &amp; Phát triển Website Chuyên Nghiệp</p>
        <p><strong>BÊN B (BÊN MUA): <?= e($contract['buyer_name']) ?></strong><br>Đại diện liên hệ: <?= e($contract['fullname']) ?><br>Mã số thuế: <?= e($contract['buyer_tax_id'] ?: 'Chưa cung cấp') ?><br>Địa chỉ: <?= e($contract['buyer_address'] ?: 'Chưa cung cấp') ?><br>Điện thoại: <?= e($contract['phone']) ?></p>
        <p><strong>Điều 1. Nội dung và bàn giao</strong><br>Bên A thực hiện dịch vụ <?= e($contract['service_type']) ?> theo phạm vi đã thống nhất; bàn giao mã nguồn/tài khoản quản trị và hướng dẫn sử dụng sau khi hoàn thành.</p>
        <p><strong>Điều 2. Giá trị hợp đồng</strong><br>Giá trị hợp đồng: <strong><?= number_format((float)$contract['final_price'], 0, ',', '.') ?> VNĐ</strong>.</p>
        <p><strong>Điều 3. Bảo hành và điều khoản</strong><br>Bên A bảo hành lỗi kỹ thuật thuộc phạm vi bàn giao; hai bên phối hợp nghiệm thu và thanh toán theo thỏa thuận.<br><?= nl2br(e($contract['terms'] ?? '')) ?></p>
        <div class="signature">
            <div><strong>ĐẠI DIỆN BÊN A</strong><br><em>(Ký, ghi rõ họ tên)</em><br><br><br>HOÀNG DƯƠNG</div>
            <div><strong>ĐẠI DIỆN BÊN B</strong><br><em>(Ký, ghi rõ họ tên)</em><br><br><br><?= e($contract['buyer_name']) ?></div>
        </div>
    </article>
</body>

</html>