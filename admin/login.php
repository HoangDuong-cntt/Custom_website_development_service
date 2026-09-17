<?php
/*
 * File: admin/login.php
 * Mục đích: Trang đăng nhập quản trị để xác thực admin và cấp quyền truy cập vào dashboard.
 * Phần: Authentication / admin management.
 */
require_once __DIR__ . '/../includes/bootstrap.php';
// Nếu admin đã đăng nhập, chuyển thẳng đến trang quản lý yêu cầu.
if (is_admin()) {
    header('Location: leads.php');
    exit;
}
$error = '';
// Xử lý xác thực form: kiểm tra khóa tạm thời, CSRF và mật khẩu người dùng.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_SESSION['login_locked_until']) && time() < (int)$_SESSION['login_locked_until']) {
        $error = 'Có quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau 5 phút.';
    }
    if (!verify_csrf()) $error = 'Phiên làm việc không hợp lệ. Vui lòng thử lại.';
    elseif ($error === '') {
        $stmt = db()->prepare("SELECT id,username,password,email,role FROM users WHERE username=? AND role='admin' LIMIT 1");
        $stmt->execute([trim($_POST['username'] ?? '')]);
        $user = $stmt->fetch();
        if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
            unset($user['password']);
            session_regenerate_id(true);
            $_SESSION['user'] = $user;
            $_SESSION['admin_logged_in'] = true;
            header('Location: leads.php');
            exit;
        }
        $_SESSION['login_failures'] = ((int)($_SESSION['login_failures'] ?? 0)) + 1;
        if ($_SESSION['login_failures'] >= 5) {
            $_SESSION['login_locked_until'] = time() + 300;
            $_SESSION['login_failures'] = 0;
        }
        $error = 'Tên đăng nhập, mật khẩu hoặc quyền quản trị không hợp lệ.';
    }
}
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Đăng nhập quản trị</title>
    <link rel="icon" type="image/svg+xml" href="../<?= e(SITE_LOGO_FILE) ?>">
    <link rel="apple-touch-icon" href="../<?= e(SITE_LOGO_FILE) ?>">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-950 text-slate-100 grid place-items-center p-5">
    <main class="w-full max-w-md">
        <div class="rounded-2xl border border-slate-700 bg-slate-900 p-8 shadow-2xl">
            <div class="mb-7 text-center">
                <img src="../<?= e(SITE_LOGO_FILE) ?>" alt="DHDTECH" class="mx-auto mb-5 h-12 w-auto drop-shadow-md brightness-0 invert">
                <h1 class="text-2xl font-bold">HoangDuongTech Admin</h1>
                <p class="mt-2 text-sm text-slate-400">Đăng nhập để truy cập khu vực quản trị.</p>
            </div><?php if ($error): ?><div class="mb-5 rounded-lg border border-red-800 bg-red-950/70 px-4 py-3 text-sm text-red-200"><?= e($error) ?></div><?php endif ?><form method="post" class="space-y-5"><input type="hidden" name="csrf" value="<?= csrf() ?>"><label class="block text-sm font-medium">Tên đăng nhập<input required autofocus name="username" class="mt-2 w-full rounded-lg border border-slate-600 bg-slate-800 px-3 py-2.5 outline-none focus:border-blue-500" autocomplete="username"></label><label class="block text-sm font-medium">Mật khẩu<input required type="password" name="password" class="mt-2 w-full rounded-lg border border-slate-600 bg-slate-800 px-3 py-2.5 outline-none focus:border-blue-500" autocomplete="current-password"></label><button class="w-full rounded-lg bg-blue-600 py-2.5 font-semibold hover:bg-blue-500">Đăng nhập an toàn</button></form>
        </div>
    </main>
</body>

</html>