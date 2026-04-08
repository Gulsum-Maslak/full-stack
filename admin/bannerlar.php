<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
//aktif sayfayı tespıt et
$activePage = basename($_SERVER['PHP_SELF']);

include_once __DIR__ . '/../bağlantı.php';

$activePage = basename($_SERVER['PHP_SELF']);
include_once __DIR__ . '/../bağlantı.php'; // Veritabanı bağlantı dosyanızın yolu

// --- İŞLEMLER ---

// EKLE
if (isset($_POST['ekle'])) {
  $baslik = $_POST['baslik'];
  $alt_baslik = $_POST['alt_baslik'];
  $sayfa_key = $_POST['sayfa_key'];

  $resim_yolu = "img/" . $_FILES['resim']['name'];
  move_uploaded_file($_FILES['resim']['tmp_name'], "../" . $resim_yolu);

  $sorgu = $db->prepare("INSERT INTO bannerlar (baslik, alt_baslik, sayfa_key, resim_yolu, aktif) VALUES (?, ?, ?, ?, 1)");
  $sorgu->execute([$baslik, $alt_baslik, $sayfa_key, $resim_yolu]);
  header("Location: bannerlar.php?durum=eklendi");
  exit;
}

// SİL
if (isset($_GET['sil'])) {
  $id = $_GET['sil'];
  $db->prepare("DELETE FROM bannerlar WHERE id=?")->execute([$id]);
  header("Location: bannerlar.php?durum=silindi");
  exit;
}

// AKTİF / PASİF
if (isset($_GET['id']) && isset($_GET['durum'])) {
  $id = $_GET['id'];
  $durum = $_GET['durum'];
  $db->prepare("UPDATE bannerlar SET aktif=? WHERE id=?")->execute([$durum, $id]);
  header("Location: bannerlar.php");
  exit;
}

// DÜZENLE
if (isset($_POST['duzenle'])) {
  $id = $_POST['id'];
  $baslik = $_POST['baslik'];
  $alt_baslik = $_POST['alt_baslik'];
  $sayfa_key = $_POST['sayfa_key'];

  if ($_FILES['resim']['name'] != "") {
    $resim_yolu = "img/" . $_FILES['resim']['name'];
    move_uploaded_file($_FILES['resim']['tmp_name'], "../" . $resim_yolu);
    $sorgu = $db->prepare("UPDATE bannerlar SET baslik=?, alt_baslik=?, sayfa_key=?, resim_yolu=? WHERE id=?");
    $sorgu->execute([$baslik, $alt_baslik, $sayfa_key, $resim_yolu, $id]);
  } else {
    $sorgu = $db->prepare("UPDATE bannerlar SET baslik=?, alt_baslik=?, sayfa_key=? WHERE id=?");
    $sorgu->execute([$baslik, $alt_baslik, $sayfa_key, $id]);
  }
  header("Location: bannerlar.php?durum=guncellendi");
  exit;
}

$bannerlar = $db->query("SELECT * FROM bannerlar ORDER BY id DESC");



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
  <style>
    body {
      background: #f4f6f9;
    }

    .banner-card {
      transition: transform 0.2s;
      border: none;
      border-radius: 12px;
      overflow: hidden;
    }

    .banner-card:hover {
      transform: translateY(-5px);
    }

    .banner-img-container {
      height: 180px;
      overflow: hidden;
      position: relative;
    }

    .banner-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .page-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      background: rgba(0, 0, 0, 0.6);
      color: #fff;
      padding: 2px 10px;
      border-radius: 20px;
      font-size: 12px;
    }

    .header-box {
      background: linear-gradient(45deg, #343a40);
      color: white;
      padding: 25px;
      border-radius: 15px;
      margin-bottom: 30px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
  </style>

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
          </div>
          <div class="container py-5">
            <div class="header-box d-flex justify-content-between align-items-center">
              <div>
                <h2 class="mb-0"><i class="bi bi-images me-2"></i> Banner Yönetimi</h2>
                <small>Tüm sayfaların üst banner görsellerini buradan yönetebilirsiniz.</small>
              </div>
              <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modalEkle">
                <i class="bi bi-plus-circle me-1"></i> Yeni Banner Ekle
              </button>
            </div>

            <div class="row">
              <?php while ($row = $bannerlar->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="col-md-4 mb-4">
                  <div class="card shadow-sm banner-card">
                    <div class="banner-img-container">
                      <span class="page-badge"><?= $row['sayfa_key'] ?></span>
                      <img src="../<?= $row['resim_yolu'] ?>" class="banner-img" alt="Banner">
                    </div>
                    <div class="card-body">
                      <h6 class="fw-bold mb-1 text-truncate"><?= $row['baslik'] ?></h6>
                      <p class="text-muted small text-truncate" style="height: 20px;"><?= $row['alt_baslik'] ?></p>
                      <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="btn-group">
                          <button class="btn btn-outline-warning btn-sm edit-button"
                            data-id="<?= $row['id'] ?>"
                            data-baslik="<?= htmlspecialchars($row['baslik']) ?>"
                            data-alt="<?= htmlspecialchars($row['alt_baslik'] ?? '') ?>"
                            data-sayfa="<?= $row['sayfa_key'] ?>">
                            <i class="bi bi-pencil"></i>
                          </button>
                          <a href="?sil=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bu bannerı silmek istediğine emin misin?')">
                            <i class="bi bi-trash"></i>
                          </a>
                        </div>

                        <?php if ($row['aktif'] == 1): ?>
                          <a href="?id=<?= $row['id'] ?>&durum=0" class="btn btn-success btn-sm rounded-pill px-3">Aktif</a>
                        <?php else: ?>
                          <a href="?id=<?= $row['id'] ?>&durum=1" class="btn btn-secondary btn-sm rounded-pill px-3">Pasif</a>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
          </div>

          <div class="modal fade" id="modalEkle" tabindex="-1">
            <div class="modal-dialog">
              <form method="POST" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                  <h5>Yeni Banner Ekle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <label>Bağlı Olduğu Sayfa (sayfa_key)</label>
                  <input type="text" name="sayfa_key" class="form-control mb-3" placeholder="Örn: index.php" required>
                  <label>Başlık</label>
                  <input type="text" name="baslik" class="form-control mb-3" required>
                  <label>Alt Başlık / Açıklama</label>
                  <textarea name="alt_baslik" class="form-control mb-3"></textarea>
                  <label>Banner Görseli</label>
                  <input type="file" name="resim" class="form-control" required>
                </div>
                <div class="modal-footer"><button type="submit" name="ekle" class="btn btn-primary w-100">Kaydet</button></div>
              </form>
            </div>
          </div>

          <div class="modal fade" id="modalDuzenle" tabindex="-1">
            <div class="modal-dialog">
              <form method="POST" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                  <h5>Banner Düzenle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="id" id="edit_id">
                  <label>Bağlı Olduğu Sayfa</label>
                  <input type="text" name="sayfa_key" id="edit_sayfa" class="form-control mb-3" required>
                  <label>Başlık</label>
                  <input type="text" name="baslik" id="edit_baslik" class="form-control mb-3" required>
                  <label>Alt Başlık / Açıklama</label>
                  <textarea name="alt_baslik" id="edit_alt_baslik" class="form-control mb-3"></textarea>
                  <label>Görseli Değiştir (Boş bırakılırsa eski resim kalır)</label>
                  <input type="file" name="resim" class="form-control">
                </div>
                <div class="modal-footer"><button type="submit" name="duzenle" class="btn btn-warning w-100">Güncelle</button></div>
              </form>
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

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Tüm düzenle butonlarını seç
      const editButtons = document.querySelectorAll('.edit-button');

      editButtons.forEach(button => {
        button.addEventListener('click', function() {
          // Buton üzerindeki data verilerini al
          const id = this.getAttribute('data-id');
          const baslik = this.getAttribute('data-baslik');
          const altBaslik = this.getAttribute('data-alt');
          const sayfa = this.getAttribute('data-sayfa');

          // Modal içindeki inputlara değerleri bas
          document.getElementById('edit_id').value = id;
          document.getElementById('edit_baslik').value = baslik;
          document.getElementById('edit_alt_baslik').value = altBaslik;
          document.getElementById('edit_sayfa').value = sayfa;

          // Modalı göster
          const modalElement = document.getElementById('modalDuzenle');
          const modalInstance = new bootstrap.Modal(modalElement);
          modalInstance.show();
        });
      });
    });
  </script>





  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"></script>
  <script src="js/adminlte.min.js"></script>

 
</body>

</html>