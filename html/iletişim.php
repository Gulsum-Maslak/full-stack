<?php 
include_once __DIR__ . '/../parts/header.php'; 
include_once __DIR__ . '/../parts/navbarMenu.php'; 
include_once __DIR__ . '/../parts/banner.php'; 

?>


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
     <!-- <div class="col-12 text-center pb-5">
        <h2 class="display-2 kartTitle">İletişim</h2>
      </div>-->
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
<?php include_once __DIR__ . '/../parts/footer.php'; ?>