<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
//aktif sayfayı tespıt et
$activePage = basename($_SERVER['PHP_SELF']);

include_once __DIR__ . '/../bağlantı.php';
// --- SEPETTEN ÜRÜN ÇIKARMA (Opsiyonel: Admin müdahalesi) ---
if (isset($_GET['sil'])) {
    $sepet_id = $_GET['sil'];
    $db->prepare("DELETE FROM sepet WHERE id = ?")->execute([$sepet_id]);
    header("Location: sepet.php?durum=silindi");
    exit;
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

 <div class="card shadow-sm border-0">
          <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-cart-fill me-2"></i>Aktif Müşteri Sepetleri</h5>
            <small class="fw-bold">Ödeme Bekleyen Ürünler</small>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light">
                  <tr>
                    <th>Müşteri (ID)</th>
                    <th>Ürün Görsel</th>
                    <th>Ürün Adı</th>
                    <th>Adet</th>
                    <th>Birim Fiyat</th>
                    <th>Toplam</th>
                    <th>İşlem</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Sepetteki ürünleri kullanıcı ID'sine göre gruplayarak çekebiliriz
                  $sepet_sorgu = $db->query("SELECT * FROM sepet ORDER BY kullanici_id ASC");
                  $toplam_sepet_tutari = 0;

                  if($sepet_sorgu->rowCount() > 0):
                    while($s = $sepet_sorgu->fetch(PDO::FETCH_ASSOC)):
                      $ara_toplam = $s['urun_fiyat'] * $s['adet'];
                      $toplam_sepet_tutari += $ara_toplam;
                  ?>
                  <tr>
                    <td><span class="badge bg-secondary">User #<?= $s['kullanici_id'] ?></span></td>
                    <td>
                      <img src="../<?= htmlspecialchars($s['urun_resim']) ?>" alt="" style="width: 50px; height: 50px; object-fit: cover;" class="rounded shadow-sm">
                    </td>
                    <td class="fw-bold"><?= htmlspecialchars($s['urun_ad']) ?></td>
                    <td><span class="badge text-bg-primary"><?= $s['adet'] ?> Adet</span></td>
                    <td><?= $s['urun_fiyat'] ?> TL</td>
                    <td class="text-success fw-bold"><?= $ara_toplam ?> TL</td>
                    <td>
                      <a href="?sil=<?= $s['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Ürünü sepetten zorla çıkartmak istediğinize emin misiniz?')">
                        <i class="bi bi-x-circle"></i>
                      </a>
                    </td>
                  </tr>
                  <?php 
                    endwhile; 
                  else:
                  ?>
                  <tr>
                    <td colspan="7" class="py-4 text-muted">Şu an aktif sepet bulunmuyor.</td>
                  </tr>
                  <?php endif; ?>
                </tbody>
                <?php if($toplam_sepet_tutari > 0): ?>
                <tfoot class="table-dark">
                  <tr>
                    <td colspan="5" class="text-end">Potansiyel Toplam Kazanç:</td>
                    <td class="text-success fw-bold"><?= $toplam_sepet_tutari ?> TL</td>
                    <td></td>
                  </tr>
                </tfoot>
                <?php endif; ?>
              </table>
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

 

</body>

</html>