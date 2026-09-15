<?php
require_once __DIR__.'/auth.php'; $pdo=db();
$leadId=(int)($_GET['lead_id']??$_POST['lead_id']??0);
$leadStmt=$pdo->prepare("SELECT * FROM leads WHERE id=? AND status='Closed_Won'"); $leadStmt->execute([$leadId]); $lead=$leadStmt->fetch();
if(!$lead){header('Location: leads.php');exit;}
$existing=$pdo->prepare('SELECT id FROM contracts WHERE lead_id=? ORDER BY id DESC LIMIT 1'); $existing->execute([$leadId]);
if($row=$existing->fetch()){header('Location: print_contract.php?id='.(int)$row['id']);exit;}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verify_csrf())$error='Phiên làm việc không hợp lệ.';
    else { $buyer=trim($_POST['buyer_name']??'');$tax=trim($_POST['buyer_tax_id']??'');$address=trim($_POST['buyer_address']??'');$price=(float)($_POST['final_price']??0);$date=$_POST['contract_date']??'';$terms=trim($_POST['terms']??'');
        $dateValid=DateTime::createFromFormat('Y-m-d',$date);
        if($buyer===''||$price<=0||!$dateValid||$dateValid->format('Y-m-d')!==$date)$error='Vui lòng nhập tên Bên B, giá chốt và ngày ký hợp lệ.';
        else { $year=date('Y',strtotime($date));$codeStmt=$pdo->prepare("SELECT COUNT(*) FROM contracts WHERE contract_code LIKE ?");$codeStmt->execute(['HD-'.$year.'-%']);$code='HD-'.$year.'-'.str_pad((string)((int)$codeStmt->fetchColumn()+1),3,'0',STR_PAD_LEFT);
            $pdo->prepare('INSERT INTO contracts(lead_id,contract_code,buyer_name,buyer_tax_id,buyer_address,final_price,contract_date,terms) VALUES(?,?,?,?,?,?,?,?)')->execute([$leadId,$code,$buyer,$tax?:null,$address?:null,$price,$date,$terms?:null]);
            header('Location: print_contract.php?id='.(int)$pdo->lastInsertId());exit;
        }
    }
}
?><!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Tạo hợp đồng</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light"><main class="container py-5" style="max-width:850px"><a href="leads.php" class="text-decoration-none">← Quay lại lead</a><h1 class="h3 mt-3">Tạo hợp đồng</h1><div class="card mt-3"><div class="card-body p-4"><div class="alert alert-light border"><strong>Khách hàng:</strong> <?=e($lead['fullname'])?> · <?=e($lead['phone'])?><br><strong>Dịch vụ:</strong> <?=e($lead['service_type'])?></div><?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif?><form method="post"><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="hidden" name="lead_id" value="<?=$leadId?>"><div class="row g-3"><div class="col-md-6"><label class="form-label">Tên Bên B (công ty/cá nhân) *</label><input required name="buyer_name" class="form-control" value="<?=e($lead['fullname'])?>"></div><div class="col-md-6"><label class="form-label">Mã số thuế</label><input name="buyer_tax_id" class="form-control"></div><div class="col-12"><label class="form-label">Địa chỉ</label><input name="buyer_address" class="form-control"></div><div class="col-md-6"><label class="form-label">Giá chốt (VNĐ) *</label><input required min="1" name="final_price" type="number" class="form-control"></div><div class="col-md-6"><label class="form-label">Ngày ký *</label><input required name="contract_date" type="date" value="<?=date('Y-m-d')?>" class="form-control"></div><div class="col-12"><label class="form-label">Điều khoản bổ sung</label><textarea name="terms" class="form-control" rows="7" placeholder="Tiến độ, bàn giao, thanh toán, bảo hành..."></textarea></div></div><button class="btn btn-primary mt-4">Tạo và xem hợp đồng</button></form></div></div></main></body></html>
