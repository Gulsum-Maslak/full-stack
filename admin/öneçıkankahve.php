<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include_once __DIR__ . '/../bağlantı.php';
$activePage = basename($_SERVER['PHP_SELF']);

// Resimlerin yükleneceği ana dizin (Senin yapına göre 'img/' içinde)
$upload_dir = "img/";

/* --- KAHVE GÜNCELLEME İŞLEMİ --- */
if (isset($_POST['kahve_guncelle'])) {
  $id = $_POST['id'];
  $baslik = $_POST['baslik'];
  $aciklama = $_POST['aciklama'];
  $fiyat_eski = $_POST['fiyat_eski'];
  $fiyat_yeni = $_POST['fiyat_yeni'];

  if (!empty($_FILES['resim']['name'])) {
    $resim_adi = "img/" . time() . "-" . basename($_FILES['resim']['name']);
    // __DIR__ . "/../" kullanarak ana dizindeki img klasörüne çıkıyoruz
    if (move_uploaded_file($_FILES['resim']['tmp_name'], __DIR__ . "/../" . $resim_adi)) {
      $guncelle = $db->prepare("UPDATE öne_çıkan SET baslik=?, aciklama=?, resim=?, fiyat_eski=?, fiyat_yeni=? WHERE id=?");
      $guncelle->execute([$baslik, $aciklama, $resim_adi, $fiyat_eski, $fiyat_yeni, $id]);
    }
  } else {
    $guncelle = $db->prepare("UPDATE öne_çıkan SET baslik=?, aciklama=?, fiyat_eski=?, fiyat_yeni=? WHERE id=?");
    $guncelle->execute([$baslik, $aciklama, $fiyat_eski, $fiyat_yeni, $id]);
  }
  header("Location: öneçıkankahve.php?durum=guncellendi");
  exit;
}

/* --- YENİ EKLE --- */
if (isset($_POST['kahve_ekle'])) {
  $resim_yolu = "img/kahve-default.jpg";
  if (!empty($_FILES['resim']['name'])) {
    $resim_adi = "img/" . time() . "-" . basename($_FILES['resim']['name']);
    if (move_uploaded_file($_FILES['resim']['tmp_name'], __DIR__ . "/../" . $resim_adi)) {
      $resim_yolu = $resim_adi;
    }
  }
  $ekle = $db->prepare("INSERT INTO öne_çıkan (baslik, aciklama, resim, fiyat_eski, fiyat_yeni) VALUES (?, ?, ?, ?, ?)");
  $ekle->execute([$_POST['baslik'], $_POST['aciklama'], $resim_yolu, $_POST['fiyat_eski'], $_POST['fiyat_yeni']]);
  header("Location: öneçıkankahve.php?durum=eklendi");
  exit;
}

/* --- SİL --- */
if (isset($_POST['sil_id'])) {
  $sil = $db->prepare("DELETE FROM öne_çıkan WHERE id = ?");
  $sil->execute([$_POST['sil_id']]);
  header("Location: öneçıkankahve.php?durum=silindi");
  exit;
}
?>
<!doctype html>
<html lang="tr">

<head>
  <meta charset="utf-8" />
  <title>Yönetim Paneli | Öne Çıkanlar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
    .resim-onizleme {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border-radius: 8px;
    }

    /* Titremeyi önlemek için animasyonları kapatıyoruz */
    .app-wrapper {
      transition: none !important;
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
      <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="fw-bold">Öne Çıkan Ürün Yönetimi</h4>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kahveEkleModal">
            <i class="bi bi-plus"></i> Yeni Ürün Ekle
          </button>
        </div>

        <div class="card border-0 shadow-sm">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-dark">
                <tr>
                  <th>ID</th>
                  <th>Görsel</th>
                  <th>Başlık</th>
                  <th>Fiyat (Eski/Yeni)</th>
                  <th class="text-center">İşlemler</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sorgu = $db->query("SELECT * FROM öne_çıkan ORDER BY id DESC");
                while ($row = $sorgu->fetch(PDO::FETCH_ASSOC)) {
                  // Resim yolu düzeltme: Eğer veritabanında img/ yoksa ekle, varsa olduğu gibi kullan
                  $img_src = (strpos($row['resim'], 'img/') === false) ? "../img/" . $row['resim'] : "../" . $row['resim'];
                ?>
                  <tr>
                    <td>#
                      <?= $row['id'] ?>
                    </td>
                    <td><img src="<?= $img_src ?>" class="resim-onizleme shadow-sm"
                        onerror="this.src='../img/kahve-default.jpg'"></td>
                    <td><strong>
                        <?= htmlspecialchars($row['baslik']) ?>
                      </strong></td>
                    <td>
                      <span class="text-decoration-line-through text-danger small">
                        <?= $row['fiyat_eski'] ?> TL
                      </span><br>
                      <span class="text-success fw-bold">
                        <?= $row['fiyat_yeni'] ?> TL
                      </span>
                    </td>
                    <td class="text-center">
                      <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                        data-bs-target="#editModal<?= $row['id'] ?>">Düzenle</button>
                      <form method="POST" class="d-inline"
                        onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?')">
                        <input type="hidden" name="sil_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Sil</button>
                      </form>

                      <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                          <form method="POST" enctype="multipart/form-data"
                            class="modal-content text-start">
                            <div class="modal-header bg-info text-white">
                              <h5 class="modal-title">Ürün Düzenle:
                                <?= $row['baslik'] ?>
                              </h5>
                              <button type="button" class="btn-close"
                                data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                              <input type="hidden" name="id" value="<?= $row['id'] ?>">
                              <div class="mb-3">
                                <label class="form-label">Ürün Başlığı</label>
                                <input type="text" name="baslik" class="form-control"
                                  value="<?= $row['baslik'] ?>" required>
                              </div>
                              <div class="mb-3">
                                <label class="form-label">Açıklama</label>
                                <textarea name="aciklama" class="form-control"
                                  rows="3"><?= $row['aciklama'] ?></textarea>
                              </div>
                              <div class="row mb-3">
                                <div class="col"><label>Eski Fiyat</label><input type="text"
                                    name="fiyat_eski" class="form-control"
                                    value="<?= $row['fiyat_eski'] ?>"></div>
                                <div class="col"><label>Yeni Fiyat</label><input type="text"
                                    name="fiyat_yeni" class="form-control"
                                    value="<?= $row['fiyat_yeni'] ?>"></div>
                              </div>
                              <div>
                                <label class="form-label">Görseli Güncelle</label>
                                <input type="file" name="resim" class="form-control">
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="submit" name="kahve_guncelle"
                                class="btn btn-info text-white w-100">Değişiklikleri
                                Kaydet</button>
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
        </div>
      </div>
    </main>
  </div>

  <div class="modal fade" id="kahveEkleModal" tabindex="-1">
    <div class="modal-dialog">
      <form method="POST" enctype="multipart/form-data" class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Yeni Kahve Ekle</h5><button type="button" class="btn-close"
            data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3"><label class="form-label">Başlık</label><input type="text" name="baslik"
              class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Açıklama</label><input type="text" name="aciklama"
              class="form-control"></div>
          <div class="row mb-3">
            <div class="col"><label>Eski Fiyat</label><input type="text" name="fiyat_eski"
                class="form-control"></div>
            <div class="col"><label>Yeni Fiyat</label><input type="text" name="fiyat_yeni"
                class="form-control"></div>
          </div>
          <div class="mb-3"><label class="form-label">Ürün Görseli</label><input type="file" name="resim"
              class="form-control"></div>
        </div>
        <div class="modal-footer"><button type="submit" name="kahve_ekle" class="btn btn-primary w-100">Ürünü
            Sisteme Ekle</button></div>
      </form>
    </div>
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