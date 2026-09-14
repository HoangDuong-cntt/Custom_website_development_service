<?php require __DIR__ . '/../includes/bootstrap.php';
require_admin();
$pdo = db();
$tab = $_GET['tab'] ?? 'dashboard';
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'lead') {
        $pdo->prepare('UPDATE leads SET status=?,note=? WHERE id=?')->execute([$_POST['status'], $_POST['note'], (int)$_POST['id']]);
        $notice = 'Đã cập nhật lead.';
    }
    if ($action === 'delete_template') {
        $pdo->prepare('DELETE FROM templates WHERE id=?')->execute([(int)$_POST['id']]);
        $notice = 'Đã xóa mẫu giao diện.';
    }
    if ($action === 'save_template') {
        $file = trim($_POST['old_thumbnail'] ?? '');
        if (!empty($_FILES['thumbnail']['name']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true) && $_FILES['thumbnail']['size'] <= 2 * 1024 * 1024) {
                $file = '../uploads/' . uniqid('tpl_', true) . '.' . $ext;
                move_uploaded_file($_FILES['thumbnail']['tmp_name'], __DIR__ . '/' . $file);
            }
        }
        $data = [(int)$_POST['category_id'], trim($_POST['title']), $file, trim($_POST['demo_link']), (float)$_POST['price'], trim($_POST['description']), $_POST['status']];
        if (!empty($_POST['id'])) {
            $data[] = (int)$_POST['id'];
            $pdo->prepare('UPDATE templates SET category_id=?,title=?,thumbnail=?,demo_link=?,price=?,description=?,status=? WHERE id=?')->execute($data);
        } else $pdo->prepare('INSERT INTO templates(category_id,title,thumbnail,demo_link,price,description,status) VALUES(?,?,?,?,?,?,?)')->execute($data);
        $notice = 'Đã lưu mẫu giao diện.';
    }
    if ($action === 'plan') {
        $pdo->prepare('UPDATE pricing_plans SET price=?,description=?,is_popular=? WHERE id=?')->execute([(float)$_POST['price'], trim($_POST['description']), isset($_POST['is_popular']) ? 1 : 0, (int)$_POST['id']]);
        $notice = 'Đã cập nhật gói giá.';
    }
    if ($action === 'feature') {
        $pdo->prepare('INSERT INTO plan_features(plan_id,feature_name,is_included) VALUES(?,?,?)')->execute([(int)$_POST['plan_id'], trim($_POST['feature_name']), isset($_POST['is_included']) ? 1 : 0]);
        $notice = 'Đã thêm tính năng.';
    }
    if ($action === 'setting') {
        $pdo->prepare('UPDATE settings SET value=? WHERE key_name=?')->execute([trim($_POST['value']), $_POST['key_name']]);
        $notice = 'Đã lưu cấu hình.';
    }
}
$stats = ['Leads mới' => $pdo->query("SELECT COUNT(*) FROM leads WHERE status='New'")->fetchColumn(), 'Tổng mẫu' => $pdo->query('SELECT COUNT(*) FROM templates')->fetchColumn(), 'Gói giá' => $pdo->query('SELECT COUNT(*) FROM pricing_plans')->fetchColumn()];
$cats = $pdo->query('SELECT * FROM categories')->fetchAll();
$settings = $pdo->query('SELECT * FROM settings')->fetchAll(); ?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Quản trị HoangDuongTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <aside class="col-lg-2 p-3 admin-sidebar">
                <h4 class="text-white mb-4">HoangDuongTech</h4><?php foreach (['dashboard' => 'Tổng quan', 'leads' => 'Yêu cầu', 'templates' => 'Kho giao diện', 'pricing' => 'Bảng giá', 'settings' => 'Cấu hình'] as $k => $v): ?><a href="?tab=<?= $k ?>" class="<?= $tab === $k ? 'bg-primary text-white' : '' ?>"><?= $v ?></a><?php endforeach ?>
                <hr class="border-secondary"><a href="logout.php">Đăng xuất</a>
            </aside>
            <main class="col-lg-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0"><?= e(['dashboard' => 'Tổng quan', 'leads' => 'Quản lý yêu cầu', 'templates' => 'Kho giao diện', 'pricing' => 'Bảng giá', 'settings' => 'Cấu hình'][$tab] ?? 'Quản trị') ?></h1><span class="text-secondary">Xin chào, <?= e($_SESSION['user']['username']) ?></span>
                </div><?php if ($notice): ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif ?>
                <?php if ($tab === 'dashboard'): ?><div class="row g-3"><?php foreach ($stats as $label => $num): ?><div class="col-md-4">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="text-secondary"><?= $label ?></div><strong class="fs-2 text-primary"><?= $num ?></strong>
                                    </div>
                                </div>
                            </div><?php endforeach ?></div>
                <?php elseif ($tab === 'leads'): $filter = $_GET['status'] ?? '';
                    $q = 'SELECT * FROM leads' . ($filter !== '' ? ' WHERE status=?' : '') . ' ORDER BY created_at DESC';
                    $s = $pdo->prepare($q);
                    $s->execute($filter !== '' ? [$filter] : []); ?><form class="mb-3"><input type="hidden" name="tab" value="leads"><select name="status" onchange="this.form.submit()" class="form-select w-auto">
                            <option value="">Tất cả trạng thái</option><?php foreach (['New', 'In-progress', 'Completed', 'Cancelled'] as $x): ?><option <?= $filter === $x ? 'selected' : '' ?>><?= $x ?></option><?php endforeach ?>
                        </select></form>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Khách hàng</th>
                                    <th>Dịch vụ</th>
                                    <th>Ngày</th>
                                    <th>Cập nhật</th>
                                </tr>
                            </thead>
                            <tbody><?php foreach ($s as $l): ?><tr>
                                        <td><strong><?= e($l['fullname']) ?></strong><br><small><?= e($l['phone']) ?> · <?= e($l['email']) ?></small></td>
                                        <td><?= e($l['service_type']) ?><br><small><?= e($l['budget']) ?></small></td>
                                        <td><?= e($l['created_at']) ?></td>
                                        <td>
                                            <form method="post" class="d-flex gap-1"><input type="hidden" name="csrf" value="<?= csrf() ?>"><input type="hidden" name="action" value="lead"><input type="hidden" name="id" value="<?= $l['id'] ?>"><select name="status" class="form-select form-select-sm"><?php foreach (['New', 'In-progress', 'Completed', 'Cancelled'] as $x): ?><option <?= $l['status'] === $x ? 'selected' : '' ?>><?= $x ?></option><?php endforeach ?></select><input name="note" value="<?= e($l['note']) ?>" class="form-control form-control-sm" placeholder="Ghi chú"><button class="btn btn-sm btn-primary">Lưu</button></form>
                                        </td>
                                    </tr><?php endforeach ?></tbody>
                        </table>
                    </div>
                <?php elseif ($tab === 'templates'): $edit = null;
                    if (!empty($_GET['edit'])) {
                        $s = $pdo->prepare('SELECT * FROM templates WHERE id=?');
                        $s->execute([(int)$_GET['edit']]);
                        $edit = $s->fetch();
                    }
                    $items = $pdo->query('SELECT t.*,c.name category FROM templates t JOIN categories c ON c.id=t.category_id ORDER BY t.id DESC')->fetchAll(); ?><div class="card mb-4">
                        <div class="card-body">
                            <h5><?= $edit ? 'Sửa' : 'Thêm' ?> mẫu</h5>
                            <form method="post" enctype="multipart/form-data" class="row g-2"><input type="hidden" name="csrf" value="<?= csrf() ?>"><input type="hidden" name="action" value="save_template"><input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>"><input type="hidden" name="old_thumbnail" value="<?= e($edit['thumbnail'] ?? '') ?>">
                                <div class="col-md-3"><input required name="title" class="form-control" value="<?= e($edit['title'] ?? '') ?>" placeholder="Tên mẫu"></div>
                                <div class="col-md-2"><select name="category_id" class="form-select"><?php foreach ($cats as $c): ?><option value="<?= $c['id'] ?>" <?= ($edit['category_id'] ?? 0) == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach ?></select></div>
                                <div class="col-md-2"><input name="price" type="number" class="form-control" value="<?= e($edit['price'] ?? '0') ?>" placeholder="Giá"></div>
                                <div class="col-md-2"><input name="demo_link" class="form-control" value="<?= e($edit['demo_link'] ?? '#') ?>" placeholder="Link demo"></div>
                                <div class="col-md-3"><input type="file" name="thumbnail" accept="image/*" class="form-control"></div>
                                <div class="col-md-9"><input name="description" class="form-control" value="<?= e($edit['description'] ?? '') ?>" placeholder="Mô tả"></div>
                                <div class="col-md-2"><select name="status" class="form-select">
                                        <option value="active">Active</option>
                                        <option value="inactive" <?= ($edit['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    </select></div>
                                <div class="col-md-1"><button class="btn btn-primary w-100">Lưu</button></div>
                            </form>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tên</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody><?php foreach ($items as $x): ?><tr>
                                    <td><?= e($x['title']) ?></td>
                                    <td><?= e($x['category']) ?></td>
                                    <td><?= number_format($x['price']) ?>đ</td>
                                    <td><a href="?tab=templates&edit=<?= $x['id'] ?>" class="btn btn-sm btn-outline-primary">Sửa</a>
                                        <form class="d-inline" method="post" onsubmit="return confirm('Xóa mẫu này?')"><input type="hidden" name="csrf" value="<?= csrf() ?>"><input type="hidden" name="action" value="delete_template"><input type="hidden" name="id" value="<?= $x['id'] ?>"><button class="btn btn-sm btn-outline-danger">Xóa</button></form>
                                    </td>
                                </tr><?php endforeach ?></tbody>
                    </table>
                    <?php elseif ($tab === 'pricing'): $plans = $pdo->query('SELECT * FROM pricing_plans ORDER BY price')->fetchAll();
                    foreach ($plans as $p): $fs = $pdo->prepare('SELECT * FROM plan_features WHERE plan_id=?');
                        $fs->execute([$p['id']]); ?><div class="card mb-3">
                            <div class="card-body">
                                <form method="post" class="row g-2 align-items-end"><input type="hidden" name="csrf" value="<?= csrf() ?>"><input type="hidden" name="action" value="plan"><input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <div class="col-md-3"><label class="form-label"><?= e($p['plan_name']) ?></label><input name="price" type="number" class="form-control" value="<?= $p['price'] ?>"></div>
                                    <div class="col-md-5"><label class="form-label">Mô tả</label><input name="description" class="form-control" value="<?= e($p['description']) ?>"></div>
                                    <div class="col-md-2">
                                        <div class="form-check"><input class="form-check-input" name="is_popular" type="checkbox" <?= $p['is_popular'] ? 'checked' : '' ?>><label class="form-check-label">Phổ biến</label></div>
                                    </div>
                                    <div class="col-md-2"><button class="btn btn-primary w-100">Lưu giá</button></div>
                                </form>
                                <div class="mt-3"><strong>Tính năng:</strong> <?php foreach ($fs as $f): ?><span class="badge text-bg-light border me-1"><?= e($f['feature_name']) ?></span><?php endforeach ?></div>
                                <form method="post" class="row g-2 mt-2"><input type="hidden" name="csrf" value="<?= csrf() ?>"><input type="hidden" name="action" value="feature"><input type="hidden" name="plan_id" value="<?= $p['id'] ?>">
                                    <div class="col-md-6"><input required name="feature_name" class="form-control" placeholder="Thêm tính năng"></div>
                                    <div class="col-md-3">
                                        <div class="form-check pt-2"><input checked class="form-check-input" type="checkbox" name="is_included"><label class="form-check-label">Được bao gồm</label></div>
                                    </div>
                                    <div class="col-md-3"><button class="btn btn-outline-primary">Thêm tính năng</button></div>
                                </form>
                            </div>
                        </div><?php endforeach ?>
                <?php elseif ($tab === 'settings'): ?><div class="card">
                        <div class="card-body">
                            <p class="text-secondary">Thay đổi thông tin hiển thị công khai và nhận thông báo.</p><?php foreach ($settings as $set): ?><form method="post" class="row g-2 mb-3"><input type="hidden" name="csrf" value="<?= csrf() ?>"><input type="hidden" name="action" value="setting"><input type="hidden" name="key_name" value="<?= e($set['key_name']) ?>"><label class="col-md-3 col-form-label"><?= e($set['key_name']) ?></label>
                                    <div class="col-md-7"><input name="value" class="form-control" value="<?= e($set['value']) ?>"></div>
                                    <div class="col-md-2"><button class="btn btn-primary w-100">Lưu</button></div>
                                </form><?php endforeach ?>
                        </div>
                    </div>
                <?php endif ?>
            </main>
        </div>
    </div>
</body>

</html>
