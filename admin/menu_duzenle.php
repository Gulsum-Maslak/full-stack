<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include_once __DIR__ . '/../bağlantı.php';

//aktif sayfayı tespıt et
$activePage = basename($_SERVER['PHP_SELF']);

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: menuler.php");
    exit;
}

$id = $_GET['id'];
$sorgu = $db->prepare("SELECT * FROM menuler WHERE id = ?");
$sorgu->execute([$id]);
$menu = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    header("Location: menuler.php");
    exit;
}

// Güncelleme İşlemi
if (isset($_POST['guncelle'])) {
    $ad = $_POST['menu_ad'];
    $sira = $_POST['sira'];
    $aktif = $_POST['aktif'];
    $icerik = $_POST['icerik'];

    $guncelle = $db->prepare("UPDATE menuler SET menu_ad=?, sira=?, aktif=?, icerik=? WHERE id=?");
    $sonuc = $guncelle->execute([$ad, $sira, $aktif, $icerik, $id]);

    if ($sonuc) {
        // Banner başlığını da otomatik güncelle
        $banner_guncelle = $db->prepare("UPDATE bannerlar SET baslik = ? WHERE sayfa_key = ?");
        $banner_guncelle->execute([$ad, $menu['menu_url']]);

        header("Location: menuler.php?durum=ok");
    } else {
        header("Location: menuler.php?durum=hata");
    }
    exit;
}
?>
<!doctype html>
<html lang="tr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sayfa Düzenle | Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance." />
    <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/adminlte.min.css" />
    <style>
        .note-editor {
            background: #fff !important;
        }

        .form-label {
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-light">
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list fs-5"></i>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <div class="navbar-search-block">
                            <form class="form-inline">
                                <div class="input-group input-group-sm">
                                    <input class="form-control form-control-navbar" type="search" placeholder="Ara..." aria-label="Search" />
                                    <div class="input-group-append">
                                        <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>
                  

                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="img/avatar2.png" class="user-image rounded-circle shadow" alt="User Image" />
                            <span class="d-none d-md-inline">GÜLSÜM MASLAK</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary">
                                <img src="img/avatar2.png" class="rounded-circle shadow" alt="User Image" />
                                <p>Web Developer</p>
                            </li>
                            <li class="user-footer">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                                <a href="admin_cikis.php" class="btn btn-default btn-flat float-end">Oturum kapat</a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="./index.php" class="brand-link">
                    <img src="img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
                    <span class="brand-text fw-light">Yönetici</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link <?php echo ($activePage == 'index.php') ? 'active' : ''; ?>">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>Kontrol Paneli</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="menuler.php" class="nav-link <?php echo ($activePage == 'menuler.php') ? 'active' : ''; ?>">

                                <p>Menüler</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="bannerlar.php" class="nav-link <?php echo ($activePage == 'bannerlar.php') ? 'active' : ''; ?>">

                                <p>Bannerlar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="öneçıkankahve.php" class="nav-link <?php echo ($activePage == 'öneçıkankahve.php') ? 'active' : ''; ?>">

                                <p>Öne Çıkan kahveler</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="subeler.php" class="nav-link <?php echo ($activePage == 'subeler.php') ? 'active' : ''; ?>">

                                <p>Şubeler</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="hakkımızda.php" class="nav-link <?php echo ($activePage == 'hakkımızda.php') ? 'active' : ''; ?>">

                                <p>Hakkımızda</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="iletişim.php" class="nav-link <?php echo ($activePage == 'iletişim.php') ? 'active' : ''; ?>">

                                <p>İletişim</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="giriş.php" class="nav-link <?php echo ($activePage == 'giriş.php') ? 'active' : ''; ?>">

                                <p>giriş</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="ödeme.php" class="nav-link <?php echo ($activePage == 'ödeme.php') ? 'active' : ''; ?>">

                                <p>ödeme</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="uye_ol.php" class="nav-link <?php echo ($activePage == 'uye_ol.php') ? 'active' : ''; ?>">

                                <p>uye_ol</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="sepet.php" class="nav-link <?php echo ($activePage == 'sepet.php') ? 'active' : ''; ?>">

                                <p>sepet</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="container py-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between">
                    <h5 class="mb-0">Düzenle: <?php echo htmlspecialchars($menu['menu_ad']); ?></h5>
                </div>
                <form method="POST" class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Menü Adı (Banner Başlığı Olur)</label>
                            <input type="text" name="menu_ad" class="form-control" value="<?php echo htmlspecialchars($menu['menu_ad']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">URL (Değiştirilemez)</label>
                            <input type="text" class="form-control bg-light" value="<?php echo $menu['menu_url']; ?>" readonly>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-danger">SAYFA İÇERİĞİ (Görsel, Renk ve Tablo Ekleyebilirsiniz)</label>
                        <textarea id="summernote" name="icerik"><?php echo $menu['icerik']; ?></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Görüntüleme Sırası</label>
                            <input type="number" name="sira" class="form-control" value="<?php echo $menu['sira']; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Yayın Durumu</label>
                            <select name="aktif" class="form-select">
                                <option value="1" <?php echo $menu['aktif'] == 1 ? 'selected' : ''; ?>>Aktif (Yayında)</option>
                                <option value="0" <?php echo $menu['aktif'] == 0 ? 'selected' : ''; ?>>Pasif (Gizli)</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" name="guncelle" class="btn btn-success btn-lg w-100 mt-3">DEĞİŞİKLİKLERİ KAYDET VE YAYINLA</button>
                </form>
            </div>
        </div>

        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline"></div>
            <strong>Telif Hakkı &copy; 2014-2026 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> Tüm hakları saklıdır.
        </footer>
    </div>


    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"></script>
    <script src="js/adminlte.min.js"></script>
    <script src="js/js.js"></script>
    




</body>

</html>