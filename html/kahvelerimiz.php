<?php
session_start();
include_once __DIR__ . '/../bağlantı.php';

// Kullanıcı oturum açmamışsa varsayılan bir ID (örneğin 1) atıyoruz
$kullanici_id = $_SESSION['user_id'] ?? 1;
$arama = isset($_GET['q']) ? trim($_GET['q']) : '';

/* AJAX SEPETE EKLEME İŞLEMİ                         */
 if (isset($_POST["islem"]) && $_POST["islem"] == "sepete_ekle") {
    $urun_id = $_POST["id"];

    // Ürün bilgilerini çek
    $sorgu = $db->prepare("SELECT * FROM kahveler WHERE id=?");
    $sorgu->execute([$urun_id]);
    $urun = $sorgu->fetch(PDO::FETCH_ASSOC);

    if ($urun) {
        $urun_ad = $urun["baslik"];
        $urun_fiyat = $urun["yeni_fiyat"];
        $urun_resim = $urun["resim"];

        // Sepette bu ürün zaten var mı kontrol et
        $kontrol = $db->prepare("SELECT id FROM sepet WHERE kullanici_id=? AND urun_ad=?");
        $kontrol->execute([$kullanici_id, $urun_ad]);
        $sepet_kaydi = $kontrol->fetch();

        if ($sepet_kaydi) {
            // Varsa adedi artır
            $db->prepare("UPDATE sepet SET adet = adet + 1 WHERE id=?")->execute([$sepet_kaydi['id']]);
        } else {
            // Yoksa yeni ekle
            $db->prepare("INSERT INTO sepet (kullanici_id, urun_ad, urun_fiyat, urun_resim, adet) VALUES (?,?,?,?,1)")
                ->execute([$kullanici_id, $urun_ad, $urun_fiyat, $urun_resim]);
        }
    }

    // Güncel toplam ürün sayısını döndür
    $sayac = $db->prepare("SELECT SUM(adet) as toplam FROM sepet WHERE kullanici_id=?");
    $sayac->execute([$kullanici_id]);
    $sonuc = $sayac->fetch();

    ob_clean();
    echo $sonuc['toplam'] ?? 0;
    exit;
}

// 47. satırdaki hatayı önleyen kısım burasıdır:
if ($arama !== '') {
    $sorgu = $db->prepare("SELECT * FROM kahveler WHERE baslik LIKE ? OR aciklama LIKE ? ORDER BY id DESC");
    $sorgu->execute(["%$arama%", "%$arama%"]);
} else {
    $sorgu = $db->prepare("SELECT * FROM kahveler ORDER BY id DESC");
    $sorgu->execute();
}
$urunler = $sorgu->fetchAll(PDO::FETCH_ASSOC);


include_once __DIR__ . '/../parts/header.php';
include_once __DIR__ . '/../parts/navbarMenu.php'; // ARAMA SONUÇLARI BURADAN GELİYOR
include_once __DIR__ . '/../parts/banner.php';
?>

<section class="featured-products py-5">
    <div class="container">
        <div class="row row-cols-1 row-cols-md-3 g-4">

            <?php
            // NAVBAR'DAN GELEN $urunler DEĞİŞKENİNİ KULLANIYORUZ
            foreach ($urunler as $kahve):
                $modalID = "kahveModal" . $kahve['id'];
            ?>

                <div class="col reveal-card">
                    <div class="card h-100 modal-tetikleyici" style="cursor:pointer;" data-modal-id="#<?php echo $modalID; ?>">
                        <img src="../<?php echo $kahve['resim']; ?>" class="card-img-top" alt="<?php echo $kahve['baslik']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $kahve['baslik']; ?></h5>
                            <p class="card-text"><?php echo $kahve['aciklama']; ?></p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <div class="price-wrapper">
                                <span class="new-price"><?php echo $kahve['yeni_fiyat']; ?> TL</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-dark kahve-ekle-btn" data-id="<?php echo $kahve['id']; ?>" data-source="kahveler">
                                Sepete Ekle
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="<?php echo $modalID; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content coffee-modal">
                            <div class="modal-header">
                                <h5 class="modal-title">Ürün Detayı</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <div class="modal-img-container">
                                    <img src="../<?php echo $kahve['resim']; ?>" class="img-fluid rounded" alt="<?php echo $kahve['baslik']; ?>">
                                </div>
                                <h2 class="product-title"><?php echo $kahve['baslik']; ?></h2>
                                <p class="product-description"><?php echo $kahve['aciklama']; ?></p>
                                <div class="product-info-badge">
                                    <span class="price-tag"><?php echo $kahve['yeni_fiyat']; ?> TL</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-close-modal" data-bs-dismiss="modal">Kapat</button>
                                <button type="button" class="btn btn-add-cart kahve-ekle-btn" data-id="<?php echo $kahve['id']; ?>" data-source="kahveler">Sepete Ekle</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>

        </div>
    </div>
</section>

<?php include_once __DIR__ . '/../parts/footer.php'; ?>