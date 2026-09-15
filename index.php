<?php require __DIR__ . '/includes/bootstrap.php';
$pdo = db();
$categories = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$templates = $pdo->query('SELECT t.*,c.slug category_slug,c.name category_name FROM templates t JOIN categories c ON c.id=t.category_id WHERE t.status="active" ORDER BY t.id DESC')->fetchAll();
$plans = $pdo->query('SELECT * FROM pricing_plans ORDER BY price')->fetchAll();
$features = $pdo->query('SELECT * FROM plan_features ORDER BY plan_id,id')->fetchAll();
$byPlan = [];
foreach ($features as $f) $byPlan[$f['plan_id']][] = $f;
$title = 'HoangDuongTech — Làm website theo yêu cầu';
require 'includes/header.php'; ?>
<main>
    <section class="hero hero-scene" data-reveal="fade-up" data-hero-section>
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-8">
                    <p class="eyebrow mb-3">Thiết kế • Lập trình • Tăng trưởng</p>
                    <h1 class="fw-bold mb-4 hero-title" data-hero-title>Làm Website Theo Yêu Cầu – Chuẩn SEO & Đầy Đủ Tính Năng</h1>
                    <p class="lead text-white-50 mb-4">Chúng tôi biến ý tưởng của bạn thành trải nghiệm số nhanh, đẹp và mang lại khách hàng.</p><a href="#dang-ky" class="btn btn-primary btn-lg me-2">Nhận Báo Giá <i class="bi bi-arrow-right"></i></a><a href="#mau-web" class="btn btn-outline-light btn-lg">Xem Mẫu Web</a>
                </div>
                <div class="col-lg-4">
                    <div class="p-4 rounded-4 bg-white text-dark shadow hero-media" data-reveal="zoom-in" data-hero-media><i class="bi bi-window-stack fs-1 text-primary"></i>
                        <h3 class="mt-3">Web sẵn sàng tăng trưởng</h3>
                        <p class="mb-0 text-secondary">Tốc độ, giao diện di động và nền tảng quản trị dễ dùng.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 stat py-3"><strong class="fs-2 text-primary">100+</strong>
                    <div>Dự án hoàn thành</div>
                </div>
                <div class="col-md-4 stat py-3"><strong class="fs-2 text-primary">5+</strong>
                    <div>Năm kinh nghiệm</div>
                </div>
                <div class="col-md-4 py-3"><strong class="fs-2 text-primary">3.7k+</strong>
                    <div>Khách hàng tin tưởng</div>
                </div>
            </div>
        </div>
    </section>
    <section id="mau-web" class="section horizontal-showcase" data-horizontal-scroll>
        <div class="container">
            <div class="text-center mb-4" data-reveal="fade-up">
                <p class="eyebrow text-primary">Kho giao diện</p>
                <h2 class="fw-bold">Chọn một điểm khởi đầu tuyệt vời</h2>
            </div>
            <div class="d-flex justify-content-center flex-wrap gap-2 mb-4"><button class="btn btn-primary active" data-category="all">Tất cả</button><?php foreach ($categories as $c): ?><button class="btn btn-outline-primary" data-category="<?= e($c['slug']) ?>"><?= e($c['name']) ?></button><?php endforeach ?></div>
            <div class="row g-4 horizontal-track"><?php foreach ($templates as $t): ?><div class="col-md-6 col-lg-4 template-item" data-category="<?= e($t['category_slug']) ?>">
                        <div class="card border-0 shadow-sm template-card overflow-hidden" data-reveal="zoom-in"><img class="template-img card-img-top" data-parallax-image src="<?= e($t['thumbnail']) ?>" alt="<?= e($t['title']) ?>">
                            <div class="card-body"><span class="small text-primary fw-semibold"><?= e($t['category_name']) ?></span>
                                <h5 class="mt-1"><?= e($t['title']) ?></h5>
                                <p class="text-secondary small"><?= e($t['description']) ?></p>
                                <div class="d-flex justify-content-between align-items-center"><strong><?= number_format((float)$t['price'], 0, ',', '.') ?>đ</strong><a target="_blank" href="<?= e($t['demo_link']) ?>" class="btn btn-sm btn-outline-primary">Xem Demo</a></div>
                            </div>
                        </div>
                    </div><?php endforeach ?></div>
        </div>
    </section>
    <section id="bang-gia" class="section bg-light pricing-showcase" data-price-section>
        <div class="container">
            <div class="text-center mb-5">
                <p class="eyebrow text-primary">Bảng giá minh bạch</p>
                <h2 class="fw-bold split-heading" data-price-heading>Gói dịch vụ phù hợp với bạn</h2>
            </div>
            <div class="row g-4 justify-content-center price-grid"><?php foreach ($plans as $p): ?><div class="col-md-6 col-lg-3" data-price-card>
                        <div class="card h-100 shadow-sm price-card <?= $p['is_popular'] ? 'popular' : '' ?>" data-parallax-card>
                            <div class="card-body p-4"><?php if ($p['is_popular']): ?><span class="badge bg-primary mb-2">Được chọn nhiều</span><?php endif ?><h4><?= e($p['plan_name']) ?></h4>
                                <div class="fs-3 fw-bold text-primary my-3"><?= number_format((float)$p['price'], 0, ',', '.') ?>đ</div>
                                <p class="text-secondary small"><?= e($p['description']) ?></p>
                                <ul class="list-unstyled small"><?php foreach ($byPlan[$p['id']] ?? [] as $f): ?><li class="mb-2"><i class="bi <?= $f['is_included'] ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-muted' ?> me-2"></i><?= e($f['feature_name']) ?></li><?php endforeach ?></ul><a href="#dang-ky" class="btn btn-outline-primary w-100">Chọn gói này</a>
                            </div>
                        </div>
                    </div><?php endforeach ?></div>
        </div>
    </section>
    <section id="cong-dung" class="section">
        <div class="container">
            <div class="text-center mb-5">
                <p class="eyebrow text-primary">Giá trị lâu dài</p>
                <h2 class="fw-bold split-heading">Website mang lại gì cho doanh nghiệp?</h2>
                <p class="text-secondary split-supporting-text">Một nền tảng số hoạt động liên tục để xây dựng thương hiệu và tạo doanh thu.</p>
            </div>
            <div class="row g-4"><?php foreach ([['bi-patch-check', 'Tăng uy tín thương hiệu', 'Website chuyên nghiệp giúp khách hàng tin tưởng bạn ngay từ lần đầu tìm kiếm.'], ['bi-clock-history', 'Tiếp cận khách hàng 24/7', 'Thông tin, sản phẩm và dịch vụ luôn sẵn sàng bất kể thời gian hay địa điểm.'], ['bi-cart-check', 'Tự động hóa bán hàng', 'Thu thập khách hàng tiềm năng, giới thiệu sản phẩm và nhận đơn hàng hiệu quả.'], ['bi-graph-down-arrow', 'Tiết kiệm chi phí marketing', 'Tối ưu hiện diện tìm kiếm và giảm phụ thuộc vào quảng cáo truyền thống.']] as $benefit): ?><div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4"><i class="bi <?= $benefit[0] ?> fs-2 text-primary"></i>
                                <h5 class="mt-3"><?= e($benefit[1]) ?></h5>
                                <p class="small text-secondary mb-0"><?= e($benefit[2]) ?></p>
                            </div>
                        </div>
                    </div><?php endforeach ?></div>
        </div>
    </section>
    <section class="section interactive-editorial bg-dark text-white" data-reveal="fade-up">
        <div class="container">
            <div class="row align-items-end g-4 mb-5">
                <div class="col-lg-7">
                    <p class="eyebrow">Một nền tảng, nhiều hướng đi</p>
                    <h2 class="display-5 fw-bold">Chọn cách website của bạn tạo ra chuyển động.</h2>
                </div>
                <div class="col-lg-4 ms-auto">
                    <p class="text-white-50 mb-0 inline-story">Từ ý tưởng đầu tiên đến trải nghiệm hoàn chỉnh, mỗi chi tiết đều được <span class="inline-carousel" aria-label="Ảnh giao diện chuyển động"><img src="<?= e($templates[0]['thumbnail'] ?? '') ?>" alt="Giao diện website"><img src="<?= e($templates[1]['thumbnail'] ?? ($templates[0]['thumbnail'] ?? '')) ?>" alt="Mẫu website khác"><img src="<?= e($templates[2]['thumbnail'] ?? ($templates[0]['thumbnail'] ?? '')) ?>" alt="Mẫu website nổi bật"></span> thiết kế để dẫn người dùng đến hành động tiếp theo.</p>
                </div>
            </div>
            <div class="row g-3 mode-cards">
                <?php foreach ([['Buy', 'Thu hút đúng khách hàng ngay từ điểm chạm đầu tiên.', 'bi-bag-check', 'mau-web'], ['Sell', 'Biến sự quan tâm thành cuộc trò chuyện và đơn hàng.', 'bi-lightning-charge', 'dang-ky'], ['Rent', 'Tạo tài sản số bền vững để doanh nghiệp tăng trưởng dài hạn.', 'bi-building-up', 'quy-trinh']] as $mode): ?>
                    <div class="col-md-4"><a class="mode-card" data-mode-card href="#<?= $mode[3] ?>"><span class="mode-icon"><i class="bi <?= $mode[2] ?>"></i></span><span class="mode-label"><?= $mode[0] ?></span><span class="mode-description"><?= $mode[1] ?></span><span class="mode-arrow" aria-hidden="true">→</span></a></div>
                <?php endforeach ?>
            </div>
        </div>
    </section>
    <section id="quy-trinh" class="section bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <p class="eyebrow text-primary">Quy trình rõ ràng</p>
                <h2 class="fw-bold split-heading">Từ ý tưởng đến bàn giao trong 4 bước</h2>
            </div>
            <div class="row g-4"><?php foreach (['Tiếp nhận' => 'Lắng nghe mục tiêu và tư vấn giải pháp.', 'Thiết kế' => 'Xây dựng giao diện đúng thương hiệu.', 'Lập trình' => 'Phát triển nhanh, chuẩn kỹ thuật.', 'Bàn giao' => 'Hướng dẫn quản trị và đồng hành.'] as $i => $desc): ?><div class="col-md-3">
                        <div class="step-num mb-3"><?= array_search($i, array_keys(['Tiếp nhận' => '', 'Thiết kế' => '', 'Lập trình' => '', 'Bàn giao' => ''])) + 1 ?></div>
                        <h5 class="split-supporting-text"><?= $i ?></h5>
                        <p class="text-secondary small split-supporting-text"><?= $desc ?></p>
                    </div><?php endforeach ?></div>
        </div>
    </section>
    <section id="dang-ky" class="section bg-primary text-white">
        <div class="container">
            <div class="row justify-content-between g-4">
                <div class="col-lg-5">
                    <p class="eyebrow">Fast-track</p>
                    <h2 class="fw-bold">Nhận tư vấn và báo giá miễn phí</h2>
                    <p class="text-white-50">Để lại thông tin, đội ngũ sẽ liên hệ trong thời gian sớm nhất.</p>
                </div>
                <div class="col-lg-6">
                    <form id="lead-form" action="api/submit_lead.php" method="post" class="bg-white text-dark p-4 rounded-4 shadow">
                        <div class="row g-3">
                            <div class="col-md-6"><input required name="fullname" class="form-control" placeholder="Họ và tên"></div>
                            <div class="col-md-6"><input required name="phone" class="form-control" placeholder="Số điện thoại"></div>
                            <div class="col-md-6"><input type="email" name="email" class="form-control" placeholder="Email"></div>
                            <div class="col-md-6"><select name="service_type" class="form-select">
                                    <option value="Website doanh nghiệp">Website doanh nghiệp</option>
                                    <option value="Website bán hàng">Website bán hàng</option>
                                    <option value="Landing page">Landing page</option>
                                </select></div>
                            <div class="col-12"><select name="budget" class="form-select">
                                    <option>Ngân sách dự kiến</option>
                                    <option>Dưới 5 triệu</option>
                                    <option>5 - 10 triệu</option>
                                </select></div>
                            <div class="col-12"><textarea name="note" class="form-control" rows="3" placeholder="Nhu cầu của bạn"></textarea></div>
                            <div class="col-12"><button class="btn btn-primary w-100">Gửi yêu cầu</button>
                                <div id="form-result"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main><?php require 'includes/footer.php'; ?>