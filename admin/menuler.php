<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
$activePage = basename($_SERVER['PHP_SELF']);
include_once __DIR__ . '/../bağlantı.php';

$sabit_sayfalar = ['index.php', 'kahvelerimiz.php', 'subelerimiz.php', 'hakkımızda.php', 'iletişim.php', 'İletişim.php'];

/* --- DURUM GÜNCELLEME (Aktif/Pasif) --- */
if (isset($_POST['durum_degistir'])) {
  $menu_id = $_POST['menu_id'];
  $yeni_durum = $_POST['yeni_durum'];
  $guncelle = $db->prepare("UPDATE menuler SET aktif = ? WHERE id = ?");
  $guncelle->execute([$yeni_durum, $menu_id]);
  echo "<script>window.location.href='menuler.php';</script>";
  exit;
}

/* --- SABİT MENÜ GÜNCELLEME --- */
if (isset($_POST['sabit_guncelle'])) {
  $id = $_POST['menu_id'];
  $ad = $_POST['menu_ad'];
  $sira = $_POST['sira'];
  $guncelle = $db->prepare("UPDATE menuler SET menu_ad = ?, sira = ? WHERE id = ?");
  $guncelle->execute([$ad, $sira, $id]);
  echo "<script>window.location.href='menuler.php?durum=guncellendi';</script>";
  exit;
}

/* --- SİLME İŞLEMİ --- */
if (isset($_POST['sil_id'])) {
  $sil_id = $_POST['sil_id'];
  $bul = $db->prepare("SELECT menu_url FROM menuler WHERE id = ?");
  $bul->execute([$sil_id]);
  $dosya = $bul->fetch(PDO::FETCH_ASSOC);

  if ($dosya) {
    $url = $dosya['menu_url'];
    $db->prepare("DELETE FROM bannerlar WHERE sayfa_key = ?")->execute([$url]);
    if (!in_array($url, $sabit_sayfalar)) {
      $fiziksel_yol = __DIR__ . '/../html/' . $url;
      if (file_exists($fiziksel_yol)) {
        unlink($fiziksel_yol);
      }
    }
  }
  $db->prepare("DELETE FROM menuler WHERE id = ?")->execute([$sil_id]);
  echo "<script>window.location.href='menuler.php?durum=silindi';</script>";
  exit;
}

/* --- YENİ SAYFA EKLEME İŞLEMİ --- */
if (isset($_POST['menu_ekle'])) {
  $menu_ad = $_POST['menu_ad'];
  $menu_url = $_POST['menu_url'];

  // --- KRİTİK EKLEME: Uzantı kontrolü ---
  if (pathinfo($menu_url, PATHINFO_EXTENSION) != 'php') {
      $menu_url .= '.php';
  }
  // --------------------------------------

  $sira = $_POST['sira'];
  $aktif = $_POST['aktif'];

  $ekle = $db->prepare("INSERT INTO menuler (menu_ad, menu_url, sira, aktif) VALUES (?, ?, ?, ?)");
  if ($ekle->execute([$menu_ad, $menu_url, $sira, $aktif])) {
    // Otomatik Banner Oluştur
    $banner_ekle = $db->prepare("INSERT INTO bannerlar (sayfa_key, baslik, resim_yolu, aktif) VALUES (?, ?, ?, ?)");
    $banner_ekle->execute([$menu_url, $menu_ad, 'img/bannerSayfalar.jpg', 1]);

    // Fiziksel Dosya Oluştur
    $dosya_yolu = __DIR__ . '/../html/' . $menu_url;
    $taslak_yolu = __DIR__ . '/taslak_sayfa.php';
    if (!file_exists($dosya_yolu)) {
      $icerik = file_exists($taslak_yolu) ? file_get_contents($taslak_yolu) : "<?php include 'header.php'; ?> <main></main> <?php include 'footer.php'; ?>";
      file_put_contents($dosya_yolu, $icerik);
    }
    echo "<script>window.location.href='menuler.php?durum=eklendi';</script>";
    exit;
  }
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

            <div class="d-flex justify-content-between align-items-center mb-4">
              <h4 class="fw-bold">Menü Yönetimi</h4>
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#menuEkleModal">
                <i class="bi bi-plus-circle me-1"></i> Yeni Sayfa Ekle
              </button>
            </div>

            <div class="card shadow-sm border-0">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                  <tr>
                    <th>Sıra</th>
                    <th>Menü Adı</th>
                    <th>URL</th>
                    <th>Durum</th>
                    <th class="text-end">İşlemler</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sorgu = $db->query("SELECT * FROM menuler ORDER BY sira ASC");
                  while ($row = $sorgu->fetch(PDO::FETCH_ASSOC)) {
                    $dosya_adi = basename($row['menu_url']);
                    $is_static = in_array($dosya_adi, $sabit_sayfalar);
                    $durum = $row['aktif'];
                  ?>
                    <tr>
                      <td><?= $row['sira'] ?></td>
                      <td><strong><?= $row['menu_ad'] ?></strong></td>
                      <td><small class="text-muted"><?= $row['menu_url'] ?></small></td>
                      <td>
                        <form method="POST">
                          <input type="hidden" name="menu_id" value="<?= $row['id'] ?>">
                          <input type="hidden" name="yeni_durum" value="<?= $durum == 1 ? 0 : 1 ?>">
                          <button type="submit" name="durum_degistir" class="btn btn-sm <?= $durum == 1 ? 'btn-success' : 'btn-secondary' ?>">
                            <?= $durum == 1 ? 'Aktif' : 'Pasif' ?>
                          </button>
                        </form>
                      </td>
                      <td class="text-end">
                        <?php if ($is_static): ?>
                          <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalDuzenle<?= $row['id'] ?>">
                            <i class="bi bi-pencil-square"></i> Düzenle
                          </button>
                        <?php else: ?>
                          <a href="menu_duzenle.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm text-white">
                            <i class="bi bi-gear-fill"></i> İçerik
                          </a>
                        <?php endif; ?>

                        <form method="POST" class="d-inline-block">
                          <input type="hidden" name="sil_id" value="<?= $row['id'] ?>">
                          <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Silmek istediğine emin misin?')">Sil</button>
                        </form>

                        <div class="modal fade" id="modalDuzenle<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" class="modal-content shadow text-start">
                              <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Sayfa Düzenle</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body p-4">
                                <input type="hidden" name="menu_id" value="<?= $row['id'] ?>">
                                <div class="mb-3 text-start">
                                  <label class="form-label fw-bold">Menü Başlığı</label>
                                  <input type="text" name="menu_ad" class="form-control" value="<?= $row['menu_ad'] ?>" required>
                                </div>
                                <div class="mb-0 text-start">
                                  <label class="form-label fw-bold">Görünüm Sırası</label>
                                  <input type="number" name="sira" class="form-control" value="<?= $row['sira'] ?>" required>
                                </div>
                              </div>
                              <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                                <button type="submit" name="sabit_guncelle" class="btn btn-primary">Kaydet</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            
            <div class="modal fade" id="menuEkleModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <form method="POST" class="modal-content shadow-lg border-0">
                  <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Yeni Sayfa Oluştur</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
                  </div>
                  <div class="modal-body p-4">
                    <div class="mb-3">
                      <label class="form-label fw-bold">Menü Adı</label>
                      <input type="text" name="menu_ad" class="form-control" placeholder="Örn: Kahve Çeşitleri" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-bold">URL (Dosya Adı)</label>
                      <div class="input-group">
                        <input type="text" name="menu_url" class="form-control" placeholder="kahveler" required>
                        <span class="input-group-text bg-light">.php</span>
                      </div>
                      <div class="form-text small text-muted italic">Uzantı yazmasanız bile sistem otomatik .php ekleyecektir.</div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Görünüm Sırası</label>
                        <input type="number" name="sira" class="form-control" value="0" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Durum</label>
                        <select name="aktif" class="form-select">
                          <option value="1">Aktif</option>
                          <option value="0">Pasif</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" name="menu_ekle" class="btn btn-success px-4">
                      <i class="bi bi-cloud-arrow-up me-1"></i> Sayfayı Oluştur
                    </button>
                  </div>
                </form>
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