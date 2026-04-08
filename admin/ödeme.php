<?php
ob_start();
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
//aktif sayfayı tespıt et
$activePage = basename($_SERVER['PHP_SELF']);
include_once __DIR__ . '/../bağlantı.php';


/**
 * 1. TABLO KONTROLÜ VE OLUŞTURMA
 * Eğer ayarlar tablosu yoksa oluşturur ve varsayılan verileri ekler.
 */
$db->query("CREATE TABLE IF NOT EXISTS `odeme_ayarlari` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `yontem_key` VARCHAR(50) UNIQUE,
    `durum` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$kontrol = $db->query("SELECT COUNT(*) FROM odeme_ayarlari")->fetchColumn();
if ($kontrol == 0) {
  $db->query("INSERT INTO odeme_ayarlari (yontem_key, durum) VALUES ('kapida_odeme', 1), ('kredi_karti', 1), ('eft_havale', 0)");
}

/**
 * 2. AJAX GÜNCELLEME İŞLEMİ
 * JavaScript'ten gelen isteği yakalar ve veritabanını günceller.
 */
if (isset($_GET['islem']) && $_GET['islem'] == 'durum_guncelle') {
  $key = $_GET['key'];
  $durum = $_GET['durum'];
  $sorgu = $db->prepare("UPDATE odeme_ayarlari SET durum = ? WHERE yontem_key = ?");
  $sonuc = $sorgu->execute([$durum, $key]);

  header('Content-Type: application/json');
  echo json_encode(['success' => $sonuc]);
  exit;
}

// Mevcut ayarları çekelim
$ayarlar_raw = $db->query("SELECT * FROM odeme_ayarlari")->fetchAll(PDO::FETCH_ASSOC);
$ayarlar = [];
foreach ($ayarlar_raw as $ayar) {
  $ayarlar[$ayar['yontem_key']] = $ayar['durum'];
}


?>
<!doctype html>
<html lang="tr">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Dashboard</title>
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
  <link rel="stylesheet" href="css/adminlte.min.css" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
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
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">

            <div class="col-md-7">
              <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                  <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Son İşlemler</h5>
                </div>
                <div class="card-body p-0">
                  <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-light">
                      <tr>
                        <th>No</th>
                        <th>Yöntem</th>
                        <th>Tutar</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $odemeler = $db->query("SELECT * FROM siparisler ORDER BY id DESC LIMIT 5");
                      while ($o = $odemeler->fetch(PDO::FETCH_ASSOC)):
                      ?>
                        <tr>
                          <td>#<?= $o['id'] ?></td>
                          <td><span class="badge bg-secondary"><?= $o['odeme_yontemi'] ?></span></td>
                          <td class="fw-bold"><?= $o['toplam'] ?> TL</td>
                          <td><?= $o['durum'] ?></td>
                          <td><?= date('H:i', strtotime($o['kayit_tarihi'])) ?></td>
                        </tr>
                      <?php endwhile; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white">
                  <h6 class="mb-0">Ödeme Yöntemleri Kontrolü</h6>
                </div>
                <div class="list-group list-group-flush">

                  <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div><i class="bi bi-cash-stack me-2 text-success"></i>Kapıda Ödeme</div>
                    <div class="form-check form-switch">
                      <input class="form-check-input status-toggle" type="checkbox" data-key="kapida_odeme" <?= $ayarlar['kapida_odeme'] ? 'checked' : ''; ?>>
                    </div>
                  </div>

                  <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div><i class="bi bi-credit-card me-2 text-primary"></i>Kredi Kartı</div>
                    <div class="form-check form-switch">
                      <input class="form-check-input status-toggle" type="checkbox" data-key="kredi_karti" <?= $ayarlar['kredi_karti'] ? 'checked' : ''; ?>>
                    </div>
                  </div>

                  <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div><i class="bi bi-bank me-2 text-info"></i>EFT / Havale</div>
                    <div class="form-check form-switch">
                      <input class="form-check-input status-toggle" type="checkbox" data-key="eft_havale" <?= $ayarlar['eft_havale'] ? 'checked' : ''; ?>>
                    </div>
                  </div>

                </div>
                <div class="card-footer bg-light text-center">
                  <small class="text-muted">Değişiklikler anında kaydedilir.</small>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </main>
    <footer class="app-footer">
      <div class="float-end d-none d-sm-inline"></div>
      <strong>Telif Hakkı &copy; 2014-2026 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> Tüm hakları saklıdır.
    </footer>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"></script>
  <script src="js/adminlte.min.js"></script>
  <script src="js/js.js"></script>


</body>

</html>