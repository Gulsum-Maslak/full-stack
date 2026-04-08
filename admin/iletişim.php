<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include_once __DIR__ . '/../bağlantı.php';
$activePage = basename($_SERVER['PHP_SELF']);

// 1. İletişim Bilgilerini Çek (Sağ taraf için)
$sorgu = $db->query("SELECT * FROM iletisim_bilgileri LIMIT 1");
$iletisim = $sorgu->fetch(PDO::FETCH_ASSOC);

// 2. Mesaj Silme İşlemi
if (isset($_GET['sil'])) {
  $sil_id = $_GET['sil'];
  $delete = $db->prepare("DELETE FROM iletisim_mesajlari WHERE id = ?");
  $delete->execute([$sil_id]);
  header("Location: iletişim.php?durum=silindi");
  exit;
}

// 3. Bilgileri Güncelleme İşlemi
if (isset($_POST['bilgileri_guncelle'])) {
  $adres = $_POST['adres'];
  $telefon = $_POST['telefon'];
  $email = $_POST['email'];
  $harita = $_POST['harita_link'];

  $guncelle = $db->prepare("UPDATE iletisim_bilgileri SET adres=?, telefon=?, email=?, harita_link=? WHERE id=?");
  $guncelle->execute([$adres, $telefon, $email, $harita, $iletisim['id']]);
  header("Location: iletişim.php?durum=ok");
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

            <div class="col-lg-6">
              <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                  <h5 class="mb-0"><i class="bi bi-envelope-paper-fill me-2"></i>Gelen Mesajlar</h5>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                      <thead class="table-light">
                        <tr>
                          <th>Gönderen</th>
                          <th>Konu</th>
                          <th>Mesaj</th>
                          <th>İşlem</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $mesajlar = $db->query("SELECT * FROM iletisim_mesajlari ORDER BY id DESC");
                        while ($m = $mesajlar->fetch(PDO::FETCH_ASSOC)):
                        ?>
                          <tr>
                            <td>
                              <div class="fw-bold"><?= htmlspecialchars($m['ad']) ?></div>
                              <small class="text-muted"><?= htmlspecialchars($m['email']) ?></small>
                            </td>
                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($m['konu']) ?></span></td>
                            <td>
                              <small class="text-truncate d-inline-block" style="max-width: 250px;">
                                <?= htmlspecialchars($m['mesaj']) ?>
                              </small>
                            </td>
                            <td>
                              <a href="?sil=<?= $m['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu mesajı silmek istediğinize emin misiniz?')">
                                <i class="bi bi-trash"></i>
                              </a>
                            </td>
                          </tr>
                        <?php endwhile; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                  <h5 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Şirket Bilgileri</h5>
                </div>
                <div class="card-body">
                  <?php if (isset($_GET['durum']) && $_GET['durum'] == 'ok'): ?>
                    <div class="alert alert-success py-2 small">Başarıyla güncellendi.</div>
                  <?php endif; ?>

                  <form method="POST">
                    <div class="mb-3">
                      <label class="form-label small fw-bold">E-posta</label>
                      <input type="email" name="email" class="form-control" value="<?= $iletisim['email'] ?>">
                    </div>
                    <div class="mb-3">
                      <label class="form-label small fw-bold">Telefon</label>
                      <input type="text" name="telefon" class="form-control" value="<?= $iletisim['telefon'] ?>">
                    </div>
                    <div class="mb-3">
                      <label class="form-label small fw-bold">Adres</label>
                      <textarea name="adres" class="form-control" rows="3"><?= $iletisim['adres'] ?></textarea>
                    </div>
                    <div class="mb-3">
                      <label class="form-label small fw-bold">Google Harita Linki (iframe src)</label>
                      <textarea name="harita_link" class="form-control" rows="3"><?= $iletisim['harita_link'] ?></textarea>
                      <div class="form-text small text-danger">Sadece 'src' içindeki URL'yi yapıştırın.</div>
                    </div>
                    <button type="submit" name="bilgileri_guncelle" class="btn btn-success w-100">
                      <i class="bi bi-check-circle me-2"></i>Bilgileri Kaydet
                    </button>
                  </form>
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