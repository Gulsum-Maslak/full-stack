<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include_once __DIR__ . '/../bağlantı.php';

// Sepet toplam adetini çek (Sayfa yenilendiğinde güncel kalması için)
$kullanici_id = $_SESSION['user_id'] ?? 1; // Oturum yoksa varsayılan 1
$sepetSay = $db->prepare("SELECT SUM(adet) as toplam FROM sepet WHERE kullanici_id=?");
$sepetSay->execute([$kullanici_id]);
$sepetAdet = $sepetSay->fetch(PDO::FETCH_ASSOC)['toplam'] ?? 0;

// Navbar Linklerini Veritabanından Çek
$sorguNav = $db->query("SELECT * FROM menuler WHERE aktif = 1 ORDER BY sira ASC", PDO::FETCH_ASSOC);
$menuler = $sorguNav->fetchAll();
$activePage = basename($_SERVER['PHP_SELF']);

// Arama Sorgusu İşlemi
$arama = isset($_GET['q']) ? trim($_GET['q']) : '';
?>


<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center menuscroll" href="<?php echo BASE_URL; ?>index.php">
      <img src="<?php echo BASE_URL; ?>img/logo.png" alt="logo" class="img-fluid mx-2">
      <span>Kahve Dünyası</span>
    </a>

    <div class="d-flex align-items-center ms-auto order-lg-3 nav-elements-wrapper">
      
      <form class="d-flex align-items-center menuscroll me-2" role="search"
        action="<?php echo BASE_URL; ?>html/kahvelerimiz.php"
        method="GET">
        
        <div class="search-container position-relative me-3">
          <input class="form-control ps-4 pe-5"
            id="searchQuery"
            name="q"
            type="text"
            placeholder="Kahve ara..."
            value="<?php echo htmlspecialchars($arama); ?>"
            autocomplete="off">

          <button class="search-icon-btn" type="submit">
            <i class="bi bi-search" style="font-size: 0.9rem;"></i>
          </button>
        </div>

        <div class="d-flex align-items-center gap-3 border-start ps-3">
          
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="#" class="btn-member text-decoration-none d-flex align-items-center <?php echo ($activePage == 'profil.php') ? 'active' : ''; ?>">
              <i class="bi bi-person-fill"></i>
              <span style="font-size: 13px; margin-left: 5px;"><?php echo explode(' ', $_SESSION['user_name'])[0]; ?></span>
            </a>
            <a href="<?php echo BASE_URL; ?>html/cikis.php" class="btn-member ms-2" title="Çıkış Yap">
              <i class="bi bi-box-arrow-right"></i>
            </a>
          <?php else: ?>
            <a href="<?php echo BASE_URL; ?>html/uye_ol.php" class="btn-member <?php echo ($activePage == 'uye_ol.php' || $activePage == 'giris.php') ? 'active' : ''; ?>">
              <i class="bi bi-person-fill"></i>
            </a>
          <?php endif; ?>

          <div class="cart-wrapper ms-2">
            <a href="<?php echo BASE_URL; ?>html/sepet.php" class="cart-icon position-relative <?php echo ($activePage == 'sepet.php') ? 'active' : ''; ?>">
              <i class="bi bi-bag-fill"></i>
              <span class="cart-badge" id="cart-badge" style="<?php echo ($sepetAdet <= 0) ? 'display:none;' : ''; ?>">
                <?php echo $sepetAdet; ?>
              </span>
            </a>
          </div>
          
        </div>
      </form>
      
      <button class="navbar-toggler menuscroll" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>

    <div class="collapse navbar-collapse order-lg-2" id="navbarSupportedContent">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0 menuscroll">
        <?php foreach ($menuler as $menu): ?>
          <li class="nav-item">
            <?php
            $url = trim($menu['menu_url']);
            // URL yapılandırması
            if ($url == 'index.php') {
              $gitilecek_yer = BASE_URL . $url;
            } else {
              $gitilecek_yer = (strpos($url, 'html/') === false) ? BASE_URL . "html/" . $url : BASE_URL . $url;
            }
            // Aktif sayfa kontrolü
            $is_active = (strpos($url, $activePage) !== false) ? 'active' : '';
            ?>
            <a class="nav-link <?php echo $is_active; ?>" href="<?php echo $gitilecek_yer; ?>">
              <?php if ($menu['menu_ad'] == 'Anasayfa'): ?>
                <i class="bi bi-house-door"></i>
              <?php else: ?>
                <?php echo $menu['menu_ad']; ?>
              <?php endif; ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

  </div>
</nav>