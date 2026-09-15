<?php
require __DIR__.'/../includes/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit(json_encode(['ok'=>false,'message'=>'Phương thức không hợp lệ.'])); }

$fullname=trim($_POST['fullname']??''); $phone=trim($_POST['phone']??''); $email=trim($_POST['email']??'');
if ($fullname==='' || $phone==='' || ($email!=='' && !filter_var($email,FILTER_VALIDATE_EMAIL))) {
    exit(json_encode(['ok'=>false,'message'=>'Vui lòng nhập họ tên, số điện thoại và email hợp lệ.']));
}
db()->prepare('INSERT INTO leads(fullname,phone,email,service_type,budget,note) VALUES(?,?,?,?,?,?)')
    ->execute([$fullname,$phone,$email?:null,trim($_POST['service_type']??''),trim($_POST['budget']??''),trim($_POST['note']??'')]);

// Email is optional: a delivery issue must never prevent the lead from being saved.
if ($email !== '') {
    $mailConfig = require __DIR__.'/../config/mail.php';
    $autoload = __DIR__.'/../vendor/autoload.php';
    if ($mailConfig['enabled'] && is_file($autoload)) {
        try {
            require_once $autoload;
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP(); $mail->Host=$mailConfig['host']; $mail->SMTPAuth=true;
            $mail->Username=$mailConfig['username']; $mail->Password=$mailConfig['password'];
            $mail->SMTPSecure=$mailConfig['encryption']; $mail->Port=(int)$mailConfig['port'];
            $mail->CharSet='UTF-8'; $mail->setFrom($mailConfig['from_email'],$mailConfig['from_name']);
            $mail->addAddress($email,$fullname); $mail->isHTML(true);
            $mail->Subject='[Xác Nhận] Yêu cầu tư vấn thiết kế website - HoangDuongTech';
            $safeName=e($fullname); $profile=e($mailConfig['profile_url']); $pricing=e($mailConfig['pricing_url']);
            $mail->Body="<div style=\"max-width:600px;margin:auto;font-family:Arial,sans-serif;color:#15213b\"><div style=\"padding:26px;background:#155eef;color:#fff\"><h2 style=\"margin:0\">HoangDuongTech</h2></div><div style=\"padding:28px;border:1px solid #e5e7eb\"><h2>Cảm ơn {$safeName}!</h2><p>Chúng tôi đã nhận được yêu cầu tư vấn thiết kế website của bạn. Chuyên viên sẽ gọi từ số <strong>0586526117</strong> trong vòng 5 phút.</p><p><a href=\"{$profile}\" style=\"display:inline-block;padding:10px 16px;background:#155eef;color:#fff;text-decoration:none;border-radius:4px\">Xem hồ sơ năng lực</a> &nbsp; <a href=\"{$pricing}\">Xem bảng giá sơ bộ</a></p><p style=\"color:#64748b\">Trân trọng,<br>HoangDuongTech</p></div></div>";
            $mail->AltBody="Cảm ơn {$fullname}. Chuyên viên sẽ gọi từ 0586526117 trong vòng 5 phút. Hồ sơ: {$mailConfig['profile_url']} | Bảng giá: {$mailConfig['pricing_url']}";
            $mail->send();
        } catch (Throwable $exception) { error_log('Lead confirmation mail failed: '.$exception->getMessage()); }
    }
}
$redirect = '../thank-you.php';
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') !== 'xmlhttprequest') { header('Location: '.$redirect, true, 303); exit; }
echo json_encode(['ok'=>true,'redirect'=>'thank-you.php']);
