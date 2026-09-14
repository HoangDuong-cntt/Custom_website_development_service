<?php require __DIR__.'/../includes/bootstrap.php'; header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit(json_encode(['ok'=>false,'message'=>'Phương thức không hợp lệ.'])); }
$fullname=trim($_POST['fullname']??''); $phone=trim($_POST['phone']??''); $email=trim($_POST['email']??'');
if ($fullname==='' || $phone==='' || ($email!=='' && !filter_var($email,FILTER_VALIDATE_EMAIL))) { exit(json_encode(['ok'=>false,'message'=>'Vui lòng nhập họ tên, số điện thoại và email hợp lệ.'])); }
db()->prepare('INSERT INTO leads(fullname,phone,email,service_type,budget,note) VALUES(?,?,?,?,?,?)')->execute([$fullname,$phone,$email?:null,trim($_POST['service_type']??''),trim($_POST['budget']??''),trim($_POST['note']??'')]);
echo json_encode(['ok'=>true,'message'=>'Đã gửi yêu cầu! Chúng tôi sẽ liên hệ bạn sớm.']);
