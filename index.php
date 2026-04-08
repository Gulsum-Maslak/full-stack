<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/bağlantı.php';

// AJAX isteği kontrolü
if (isset($_POST['islem']) && $_POST['islem'] == 'sepete_ekle') {
    $öne_çıkan_id = $_POST['id'];
    $kullanici_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

    // 1. Önce öne çıkan tablosundan veriyi al
    $sorgu = $db->prepare("SELECT * FROM öne_çıkan WHERE id = ?");
    $sorgu->execute([$öne_çıkan_id]);
    $öne_urun = $sorgu->fetch(PDO::FETCH_ASSOC);

    if ($öne_urun) {
        $urun_ad = $öne_urun['baslik'];
        $urun_fiyat = $öne_urun['fiyat_yeni'];
        $urun_resim = $öne_urun['resim'];

        // 2. Sepette bu ürün var mı kontrol et
        $kontrol = $db->prepare("SELECT id FROM sepet WHERE kullanici_id = ? AND urun_ad = ?");
        $kontrol->execute([$kullanici_id, $urun_ad]);
        $sepet_kaydi = $kontrol->fetch();

        if ($sepet_kaydi) {
            // Varsa adedi artır
            $db->prepare("UPDATE sepet SET adet = adet + 1 WHERE id = ?")->execute([$sepet_kaydi['id']]);
        } else {
            // Yoksa yeni kayıt ekle (Senin tablonun sütun isimlerine göre: urun_ad, urun_fiyat, urun_resim)
            $db->prepare("INSERT INTO sepet (kullanici_id, urun_ad, urun_fiyat, urun_resim, adet) VALUES (?, ?, ?, ?, 1)")
               ->execute([$kullanici_id, $urun_ad, $urun_fiyat, $urun_resim]);
        }
    }

    // Toplam sepet adedini döndür (Navbar sayacı için)
    $sayac_sorgu = $db->prepare("SELECT SUM(adet) as toplam FROM sepet WHERE kullanici_id = ?");
    $sayac_sorgu->execute([$kullanici_id]);
    $sonuc = $sayac_sorgu->fetch();
    
    ob_clean();
    echo $sonuc['toplam'] ? $sonuc['toplam'] : 0;
    exit;
}

// Navbar'daki başlangıç değeri için
$sepet_sayac_sorgu = $db->prepare("SELECT SUM(adet) as toplam FROM sepet WHERE kullanici_id = ?");
$sepet_sayac_sorgu->execute([isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1]);
$sepet_sayac_res = $sepet_sayac_sorgu->fetch();
$sepetAdet = $sepet_sayac_res['toplam'] ? $sepet_sayac_res['toplam'] : 0;


include_once __DIR__ . '/parts/header.php'; 
include_once __DIR__ . '/parts/navbarMenu.php'; 
include_once __DIR__ . '/parts/banner.php'; 
?>

<!--kart öne çıkanlar star-->
<?php
$stmt = $db->prepare("SELECT * FROM öne_çıkan ORDER BY eklenme_tarihi DESC");
$stmt->execute();
$öne_çıkan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="featured-products pb-5">
  <div class="container">
    <h2 class="display-2 text-center my-5 kartTitle">Öne Çıkanlar</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach($öne_çıkan as $urun): ?>
      <div class="col reveal-card">
    <div class="card h-100">
        <a href="<?= htmlspecialchars($urun['link']) ?>" class="text-decoration-none text-dark">
            <img src="<?= htmlspecialchars($urun['resim']) ?>" class="card-img-top" alt="<?= htmlspecialchars($urun['baslik']) ?>">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($urun['baslik']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($urun['aciklama']) ?></p>
            </div>
        </a>
        
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="price-wrapper">
                <span class="old-price"><?= number_format($urun['fiyat_eski'], 2) ?> TL</span>
                <span class="new-price"><?= number_format($urun['fiyat_yeni'], 2) ?> TL</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-dark sepete-ekle-btn" data-id="<?= $urun['id'] ?>" data-source="onecikan">
                Sepete Ekle
            </button>
        </div>
    </div>
</div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<!--kart çıkanlar end-->

<!--şubeler start-->
<?php
// Veritabanından çek
$stmt = $db->prepare("SELECT * FROM şube ORDER BY eklenme_tarihi DESC");
$stmt->execute();
$şube = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="section-dark pb-5">
  <div class="container">
    <h2 class="display-2 text-center py-5 kartTitle">Şubelerimiz</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
      <?php foreach($şube as $şubeler): ?>
      <div class="col reveal-card">
        <a href="html/subelerimiz.php">
          <div class="card text-bg-dark">
            <img src="<?= htmlspecialchars($şubeler['resim']) ?>" class="card-img" alt="<?= htmlspecialchars($şubeler['baslik']) ?>">
            <div class="card-img-overlay">
              <h5 class="card-title"><?= htmlspecialchars($şubeler['baslik']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($şubeler['aciklama']) ?></p>
            </div>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<!--şubeler end-->

<!--hakkında start-->
<?php
$stmt = $db->prepare("SELECT * FROM hakkimizda ORDER BY id DESC LIMIT 1");
$stmt->execute();
$hakkimizda = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<section class="about-section py-5">
  <div class="container">
    <div class="row text-center">

      <div class="col-12 mb-5">
        <h2 class="display-2 text-center pb-5 kartTitle"> <?= htmlspecialchars($hakkimizda['ust_baslik']) ?></h2>
        <h6 class="display-6 fw-bold mb-4"> <?= htmlspecialchars($hakkimizda['alt_baslik']) ?></h6>
        <p class="lead mx-auto" style="max-width: 800px;">
           <?= htmlspecialchars($hakkimizda['aciklama']) ?>
        </p>
      </div>

      <div class="col-md-3 col-6 mb-4 mb-md-0 border-end border-dark hkkmzdcard">
        <h2 class="fw-bold display-5"><?= htmlspecialchars($hakkimizda['deneyim_sayi']) ?></h2>
        <p class="small text-uppercase"> <?= htmlspecialchars($hakkimizda['deneyim_baslik']) ?></p>
      </div>

      <div class="col-md-3 col-6 mb-4 mb-md-0 border-end border-dark hkkmzdcard">
        <h2 class="fw-bold display-5"><?= htmlspecialchars($hakkimizda['cekirdek_sayi']) ?></h2>
        <p class="small text-uppercase"><?= htmlspecialchars($hakkimizda['cekirdek_baslik']) ?></p>
      </div>

      <div class="col-md-3 col-6 border-end border-dark hkkmzdcard">
        <h2 class="fw-bold display-5"> <?= htmlspecialchars($hakkimizda['sube_sayi']) ?></h2>
        <p class="small text-uppercase"> <?= htmlspecialchars($hakkimizda['sube_baslik']) ?></p>
      </div>

      <div class="col-md-3 col-6 hkkmzdcard">
        <h2 class="fw-bold display-5"><?= htmlspecialchars($hakkimizda['musteri_sayi']) ?></h2>
        <p class="small text-uppercase"><?= htmlspecialchars($hakkimizda['musteri_baslik']) ?></p>
      </div>

    </div>
  </div>
</section>
<!--hakkında end-->

<!--iletişim start-->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ad = trim($_POST['ad']);
    $email = trim($_POST['email']);
    $konu = trim($_POST['konu']);
    $mesaj = trim($_POST['mesaj']);

    if (!empty($ad) && !empty($email) && !empty($konu) && !empty($mesaj)) {

        $stmt = $db->prepare("INSERT INTO iletisim_mesajlari (ad, email, konu, mesaj) VALUES (?, ?, ?, ?)");
        $stmt->execute([$ad, $email, $konu, $mesaj]);

        echo "<script>alert('Mesajınız başarıyla gönderildi!');</script>";
    } else {
        echo "<script>alert('Lütfen tüm alanları doldurun!');</script>";
    }
}
//iletişim bilileri verıtabanından çekme
$stmt = $db->prepare("SELECT * FROM iletisim_bilgileri LIMIT 1");
$stmt->execute();
$iletisim = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<section class="section-dark py-5">
  <div class="container">
    <div class="row g-5">
      <div class="col-12 text-center pb-5">
        <h2 class="display-2 kartTitle">İletişim</h2>
      </div>
      <div class="col-lg-6 ltşmform1">
        <h2 class="display-5 fw-bold mb-4">Bize Yazın</h2>
        <p class="text-muted mb-4 text">Sorularınız, önerileriniz veya sadece kahve sohbeti için buradayız.</p>
        
        <form method="POST">
          <div class="row g-3">
            <div class="col-md-6">
              <input type="text" name="ad" class="form-control custom-input" placeholder="Adınız">
            </div>
            <div class="col-md-6">
              <input type="email" name="email" class="form-control custom-input" placeholder="E-posta Adresiniz">
            </div>
            <div class="col-12">
              <input type="text" name="konu" class="form-control custom-input" placeholder="Konu">
            </div>
            <div class="col-12">
              <textarea name="mesaj" class="form-control custom-input" rows="5" placeholder="Mesajınız"></textarea>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-lg w-100">Gönder</button>
            </div>
          </div>
        </form>
      </div>

      <div class="col-lg-6 ltşmform2">
        <div class="contact-info-box p-4 rounded-4 bg-white shadow-sm mb-4">
          <div class="d-flex align-items-center mb-3">
            <i class="bi bi-geo-alt-fill text-danger fs-3 me-3"></i>
            <div>
              <h6 class="mb-0 fw-bold">Merkez Ofis</h6>
              <p class="mb-0 text-muted"><?= htmlspecialchars($iletisim['adres']) ?></p>
            </div>
          </div>
          <div class="d-flex align-items-center mb-3">
            <i class="bi bi-telephone-fill text-success fs-3 me-3"></i>
            <div>
              <h6 class="mb-0 fw-bold">Telefon</h6>
              <p class="mb-0 text-muted"><?= htmlspecialchars($iletisim['telefon']) ?></p>
            </div>
          </div>
          <div class="d-flex align-items-center">
            <i class="bi bi-envelope-at-fill text-primary fs-3 me-3"></i>
            <div>
              <h6 class="mb-0 fw-bold">E-posta</h6>
              <p class="mb-0 text-muted"><?= htmlspecialchars($iletisim['email']) ?></p>
            </div>
          </div>
        </div>
        
        <div class="map-container rounded-4 overflow-hidden shadow-sm" style="height: 280px;">
          <iframe src="<?= htmlspecialchars($iletisim['harita_link']) ?>" 
          style="width:100%; height:100%; border:0;"
          loading="lazy"  allowfullscreen="" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>

    </div>
  </div>
</section>
<!--iletişim end-->

<?php include_once __DIR__ . '/parts/footer.php'; ?>