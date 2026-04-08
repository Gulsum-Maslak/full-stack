<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
//aktif sayfayı tespıt et
$activePage = basename($_SERVER['PHP_SELF']);
include_once __DIR__ . '/../bağlantı.php';

/* --- ŞUBE GÜNCELLEME --- */
if (isset($_POST['sube_guncelle'])) {
  $id = $_POST['id'];
  $baslik = $_POST['baslik'];
  $aciklama = $_POST['aciklama'];
  $aktif = isset($_POST['aktif']) ? 1 : 0;

  if (!empty($_FILES['img']['name'])) {
    $resim_adi = "img/subeler/" . time() . "-" . basename($_FILES['img']['name']);
    if (move_uploaded_file($_FILES['img']['tmp_name'], __DIR__ . "/../" . $resim_adi)) {
      $guncelle = $db->prepare("UPDATE şubeler SET baslik=?, aciklama=?, img=?, aktif=? WHERE id=?");
      $guncelle->execute([$baslik, $aciklama, $resim_adi, $aktif, $id]);
    }
  } else {
    $guncelle = $db->prepare("UPDATE şubeler SET baslik=?, aciklama=?, aktif=? WHERE id=?");
    $guncelle->execute([$baslik, $aciklama, $aktif, $id]);
  }
  header("Location: subeler.php?durum=guncellendi");
  exit;
}

/* --- YENİ ŞUBE EKLE --- */
if (isset($_POST['sube_ekle'])) {
  $resim_yolu = "img/sube-default.jpg";
  if (!empty($_FILES['img']['name'])) {
    $resim_adi = "img/subeler/" . time() . "-" . basename($_FILES['img']['name']);
    if (move_uploaded_file($_FILES['img']['tmp_name'], __DIR__ . "/../" . $resim_adi)) {
      $resim_yolu = $resim_adi;
    }
  }
  $ekle = $db->prepare("INSERT INTO şubeler (baslik, aciklama, img, aktif) VALUES (?, ?, ?, ?)");
  $ekle->execute([$_POST['baslik'], $_POST['aciklama'], $resim_yolu, 1]);
  header("Location: subeler.php?durum=eklendi");
  exit;
}

/* --- ŞUBE SİL --- */
if (isset($_POST['sil_id'])) {
  $sil = $db->prepare("DELETE FROM şubeler WHERE id = ?");
  $sil->execute([$_POST['sil_id']]);
  header("Location: subeler.php?durum=silindi");
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
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h4 class="fw-bold"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Şubelerimiz</h4>
              <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#subeEkleModal">
                <i class="bi bi-plus-circle"></i> Yeni Şube Ekle
              </button>
            </div>

            <div class="row">
              <?php
              $sorgu = $db->query("SELECT * FROM şubeler ORDER BY id DESC");
              while ($row = $sorgu->fetch(PDO::FETCH_ASSOC)) {
                $aktif_label = $row['aktif'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Pasif</span>';
              ?>
                <div class="col-md-3 mb-4">
                  <div class="card h-100 shadow-sm border-0">
                    <img src="../<?= $row['img'] ?>" class="card-img-top" style="height: 180px; object-fit: cover;" onerror="this.src='../img/sube-default.jpg'">
                    <div class="card-body">
                      <div class="d-flex justify-content-between">
                        <h6 class="fw-bold"><?= $row['baslik'] ?></h6>
                        <?= $aktif_label ?>
                      </div>
                      <p class="small text-muted text-truncate"><?= $row['aciklama'] ?></p>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex justify-content-between">
                      <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#subeEdit<?= $row['id'] ?>">Düzenle</button>
                      <form method="POST" onsubmit="return confirm('Şubeyi silmek üzeresiniz?')">
                        <input type="hidden" name="sil_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Sil</button>
                      </form>
                    </div>
                  </div>
                </div>

                <div class="modal fade" id="subeEdit<?= $row['id'] ?>" tabindex="-1">
                  <div class="modal-dialog">
                    <form method="POST" enctype="multipart/form-data" class="modal-content">
                      <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Şube Güncelle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <div class="mb-3">
                          <label class="form-label">Şube Adı</label>
                          <input type="text" name="baslik" class="form-control" value="<?= $row['baslik'] ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Adres / Açıklama</label>
                          <textarea name="aciklama" class="form-control" rows="3"><?= $row['aciklama'] ?></textarea>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Şube Görseli</label>
                          <input type="file" name="img" class="form-control">
                        </div>
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" name="aktif" <?= $row['aktif'] ? 'checked' : '' ?>>
                          <label class="form-check-label">Sitede Gösterilsin mi?</label>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" name="sube_guncelle" class="btn btn-primary w-100">Güncelle</button>
                      </div>
                    </form>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>

          <div class="modal fade" id="subeEkleModal" tabindex="-1">
            <div class="modal-dialog">
              <form method="POST" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header bg-success text-white">
                  <h5 class="modal-title">Yeni Şube Kaydı</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Şube Adı</label>
                    <input type="text" name="baslik" class="form-control" placeholder="Örn: Beşiktaş Şubesi" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Açıklama</label>
                    <textarea name="aciklama" class="form-control" placeholder="Adres ve çalışma saatleri..."></textarea>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Görsel</label>
                    <input type="file" name="img" class="form-control">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" name="sube_ekle" class="btn btn-success w-100">Şubeyi Ekle</button>
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