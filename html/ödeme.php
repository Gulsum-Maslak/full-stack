<?php 
ob_start();
session_start();
include_once __DIR__ . '/../bağlantı.php';

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 1. KULLANICI KİMLİĞİ (En önemli kısım)
// Eğer giriş yapılmışsa session'daki ID'yi al, yoksa test için 1'e zorla
$kullanici_id = (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : 1;

$siparis_id = 0;
$siparisBasarili = false;

// 2. SEPETİ ÇEK
$stmt = $db->prepare("SELECT * FROM sepet WHERE kullanici_id = ?");
$stmt->execute([$kullanici_id]);
$urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hesaplamalar
$araToplam = 0;
foreach($urunler as $urun){
    $araToplam += (float)$urun['urun_fiyat'] * (int)$urun['adet'];
}
$kargo = ($araToplam > 0) ? 20 : 0;
$genelToplam = $araToplam + $kargo;

// 3. SİPARİŞİ KAYDET
if(isset($_POST["siparis_ver"]) && !empty($urunler)){
    try {
        $db->beginTransaction();

        // A. Sipariş Ana Tablosu
        // Sütun isimlerinin SQL'dekiyle aynı olduğundan emin olun (kullanici_id)
        $sorgu = "INSERT INTO siparisler 
                  (kullanici_id, teslimat_adres, telefon, odeme_yontemi, ara_toplam, kargo, toplam) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $ekle = $db->prepare($sorgu);
        $ekle->execute([
            $kullanici_id, // Burası boş kalıyorsa session bozuktur, yukarıda 1 yaptık.
            $_POST["adres"], 
            $_POST["telefon"], 
            $_POST["odeme"], 
            $araToplam, 
            $kargo, 
            $genelToplam
        ]);

        // B. YENİ OLUŞAN ID'Yİ YAKALA
        $siparis_id = $db->lastInsertId();

        // C. Sipariş Detaylarını Aktar
        $detaySorgu = $db->prepare("INSERT INTO siparis_detay 
                      (siparis_id, urun_ad, urun_fiyat, adet, urun_resim) 
                      VALUES (?, ?, ?, ?, ?)");

        foreach($urunler as $urun){
            $detaySorgu->execute([
                $siparis_id, // siparis_detay tablosundaki siparis_id buraya yazılır
                $urun['urun_ad'], 
                $urun['urun_fiyat'], 
                $urun['adet'], 
                $urun['urun_resim']
            ]);
        }

        // D. Sepeti Temizle
        $db->prepare("DELETE FROM sepet WHERE kullanici_id = ?")->execute([$kullanici_id]);

        $db->commit();
        $siparisBasarili = true;
        $gosterilecekUrunler = $urunler;
        $urunler = []; 

    } catch (Exception $e) {
        if ($db->inTransaction()) { $db->rollBack(); }
        die("Hata Oluştu: " . $e->getMessage());
    }
}

// Görünüm parçalarını ekle
include_once __DIR__ . '/../parts/header.php'; 
include_once __DIR__ . '/../parts/navbarMenu.php'; 
include_once __DIR__ . '/../parts/banner.php';
?>

<section class="checkout-section py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-7">
        <div class="checkout-wrapper p-4 rounded-4 shadow-sm bg-white border">
          <?php if(!$siparisBasarili): ?>
            <form method="POST">
                <h4 class="fw-bold mb-4">Teslimat Bilgileri</h4>
                <div class="mb-3"><input type="text" name="ad_soyad" class="form-control" placeholder="Ad Soyad" required></div>
                <div class="mb-3"><input type="text" name="telefon" class="form-control" placeholder="Telefon" required></div>
                <div class="mb-3"><textarea name="adres" class="form-control" rows="3" placeholder="Açık Adres" required></textarea></div>
                
                <h5 class="mt-4 mb-3 fw-bold">Ödeme Yöntemi</h5>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="odeme" id="kapida" value="Kapıda Ödeme" checked onclick="kartFormuGoster(false)">
                    <label class="form-check-label" for="kapida">Kapıda Ödeme</label>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="odeme" id="krediKarti" value="Kredi Kartı" onclick="kartFormuGoster(true)">
                    <label class="form-check-label" for="krediKarti">Kredi Kartı (Simülasyon)</label>
                </div>

                <div id="simulasyonKartAlani" style="display:none; padding: 15px; background: #f8f9fa; border-radius: 10px; border: 1px solid #ddd;" class="mb-4">
                    <div class="mb-2">
                        <label class="small fw-bold text-muted">Kart Numarası</label>
                        <input type="text" class="form-control form-control-sm" placeholder="0000 0000 0000 0000" maxlength="19">
                    </div>
                    <div class="row g-2">
                        <div class="col-7">
                            <label class="small fw-bold text-muted">S.K.T</label>
                            <input type="text" class="form-control form-control-sm" placeholder="AA/YY" maxlength="5">
                        </div>
                        <div class="col-5">
                            <label class="small fw-bold text-muted">CVV</label>
                            <input type="password" class="form-control form-control-sm" placeholder="***" maxlength="3">
                        </div>
                    </div>
                    <p class="text-muted mt-2 mb-0" style="font-size: 11px;">* Bu bir ödev simülasyonudur, banka bağlantısı kurulmaz.</p>
                </div>

                <button type="submit" name="siparis_ver" class="btn btn-lg w-100 btn-primary py-3 fw-bold">Siparişi Onayla</button>
            </form>

<script>
function kartFormuGoster(durum) {
    var alan = document.getElementById('simulasyonKartAlani');
    if(durum) {
        alan.style.display = 'block';
    } else {
        alan.style.display = 'none';
    }
}
</script>
              <?php else: ?>
            <div class="text-center py-5">
                <h3 class="text-success mb-3">✓ Sipariş Başarıyla Alındı!</h3>
                <p>Sipariş No: <strong>#<?php echo $siparis_id; ?></strong></p>
                <a href="<?php echo BASE_URL; ?>index.php" class="btn btn-dark mt-3">Anasayfaya Dön</a>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="checkout-summary p-4 rounded-4 shadow-sm bg-light border">
          <h5 class="fw-bold mb-4">Sipariş Özeti</h5>
          <?php 
          $liste = $siparisBasarili ? $gosterilecekUrunler : $urunler;
          if(!empty($liste)):
              foreach($liste as $urun): ?>
                <div class="d-flex justify-content-between mb-2">
                    <span><?php echo $urun['urun_ad']; ?> (x<?php echo $urun['adet']; ?>)</span>
                    <span class="fw-bold"><?php echo ($urun['urun_fiyat'] * $urun['adet']); ?> TL</span>
                </div>
              <?php endforeach;
          else: ?>
              <p class="text-muted">Sepetinizde ürün bulunmamaktadır.</p>
          <?php endif; ?>
          <hr>
          <div class="d-flex justify-content-between"><span>Ara Toplam</span><span><?php echo $araToplam; ?> TL</span></div>
          <div class="d-flex justify-content-between"><span>Kargo</span><span><?php echo $kargo; ?> TL</span></div>
          <hr>
          <div class="d-flex justify-content-between fw-bold fs-5 color">
            <span>Toplam</span>
            <span><?php echo $genelToplam; ?> TL</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<?php include_once __DIR__ . '/../parts/footer.php'; ?>