<?php
ob_start();
session_start();
include_once __DIR__ . '/../bağlantı.php';

// Kullanıcı oturum açmamışsa varsayılan bir ID (örneğin 1) atıyoruz
$kullanici_id = $_SESSION['user_id'] ?? 1;

// AJAX işlemleri
if (isset($_POST['ajax_islem'])) {
    $id = $_POST['id'];
    $islem = $_POST['islem'];

    if ($islem == 'arttir') {
        $db->prepare("UPDATE sepet SET adet = adet + 1 WHERE id=? AND kullanici_id=?")->execute([$id, $kullanici_id]);
    } elseif ($islem == 'azalt') {
        // Önce adet kontrol et
        $urunSorgu = $db->prepare("SELECT adet FROM sepet WHERE id=? AND kullanici_id=?");
        $urunSorgu->execute([$id, $kullanici_id]);
        $urun = $urunSorgu->fetch(PDO::FETCH_ASSOC);
        if ($urun && $urun['adet'] > 1) {
            $db->prepare("UPDATE sepet SET adet = adet - 1 WHERE id=? AND kullanici_id=?")->execute([$id, $kullanici_id]);
        } elseif ($urun && $urun['adet'] == 1) {
            $db->prepare("DELETE FROM sepet WHERE id=? AND kullanici_id=?")->execute([$id, $kullanici_id]);
            $islem = 'sil'; // Silindi olarak işaretle
        }
    } elseif ($islem == 'sil') {
        $db->prepare("DELETE FROM sepet WHERE id=? AND kullanici_id=?")->execute([$id, $kullanici_id]);
    }

    // Güncel sepet verilerini çek
    $sepetSorgu = $db->prepare("SELECT * FROM sepet WHERE kullanici_id=?");
    $sepetSorgu->execute([$kullanici_id]);
    $urunler = $sepetSorgu->fetchAll(PDO::FETCH_ASSOC);

    $araToplam = 0;
    foreach ($urunler as $urun) {
        $araToplam += $urun['urun_fiyat'] * $urun['adet'];
    }

    // Silinen ürün için yeni adet
    $yeni_adet = 0;
    $urun_toplam = 0;
    if ($islem != 'sil') {
        $urunSorgu = $db->prepare("SELECT adet, urun_fiyat FROM sepet WHERE id=? AND kullanici_id=?");
        $urunSorgu->execute([$id, $kullanici_id]);
        $urun = $urunSorgu->fetch(PDO::FETCH_ASSOC);
        if ($urun) {
            $yeni_adet = $urun['adet'];
            $urun_toplam = number_format($urun['urun_fiyat'] * $urun['adet'], 2);
        }
    }

    // Sepet toplam adet
    $toplamSorgu = $db->prepare("SELECT SUM(adet) as toplam FROM sepet WHERE kullanici_id=?");
    $toplamSorgu->execute([$kullanici_id]);
    $sepet_toplam = $toplamSorgu->fetch(PDO::FETCH_ASSOC)['toplam'] ?? 0;

    $response = [
        'status' => 'success',
        'yeni_adet' => $yeni_adet,
        'urun_toplam' => $urun_toplam,
        'ara_toplam' => number_format($araToplam, 2),
        'genel_toplam' => number_format($araToplam, 2),
        'sepet_bos' => empty($urunler),
        'sepet_toplam' => $sepet_toplam
    ];

    ob_clean();
    echo json_encode($response);
    exit;
}

// Sepet verilerini çek
$sepetSorgu = $db->prepare("SELECT * FROM sepet WHERE kullanici_id=?");
$sepetSorgu->execute([$kullanici_id]);
$urunler = $sepetSorgu->fetchAll(PDO::FETCH_ASSOC);

$araToplam = 0;
foreach ($urunler as $urun) {
    $araToplam += $urun['urun_fiyat'] * $urun['adet'];
}

include_once __DIR__ . '/../parts/header.php';
include_once __DIR__ . '/../parts/navbarMenu.php';
?>

<section class="cart-section py-5 bg-light" style="min-height: 80vh;">
    <div class="container">
        <h2 class="fw-bold mb-4">Sepetim</h2>
        <div class="row g-4">
            <div class="col-lg-8" id="cart-items-container">
                <?php if(empty($urunler)): ?>
                    <div class="alert alert-info text-center p-5">Sepetiniz şu an boş.</div>
                <?php else: ?>
                    <?php foreach($urunler as $urun): 
                        $toplam = $urun['urun_fiyat'] * $urun['adet'];
                        $araToplam += $toplam;
                    ?>
                    <div class="cart-item p-3 mb-3 rounded-4 shadow-sm bg-white d-flex align-items-center justify-content-between" id="row-<?php echo $urun['id']; ?>">
                        <div class="d-flex align-items-center gap-3">
                            <img src="../<?php echo $urun['urun_resim']; ?>" class="rounded-3" style="width:80px; height:80px; object-fit:cover;">
                            <div>
                                <h5 class="mb-1 fw-bold"><?php echo $urun['urun_ad']; ?></h5>
                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-sm btn-outline-dark" onclick="sepetGuncelle(<?php echo $urun['id']; ?>, 'azalt')">-</button>
                                    <span class="fw-bold px-2" id="adet-<?php echo $urun['id']; ?>"><?php echo $urun['adet']; ?></span>
                                    <button class="btn btn-sm btn-outline-dark" onclick="sepetGuncelle(<?php echo $urun['id']; ?>, 'arttir')">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-bold mb-1"><span id="toplam-<?php echo $urun['id']; ?>"><?php echo number_format($toplam, 2); ?></span> TL</h5>
                            <button class="btn btn-sm text-danger p-0 border-0" onclick="sepetGuncelle(<?php echo $urun['id']; ?>, 'sil')">Sil</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 rounded-4 summary-card">
                    <h4 class="fw-bold mb-4">Sipariş Özeti</h4>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ara Toplam</span>
                        <span class="fw-bold"><span id="ara-toplam"><?php echo number_format($araToplam, 2); ?></span> TL</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Kargo</span>
                        <span id="kargo-durum" class="fw-bold text-success">Ücretsiz</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-5 fw-bold">Toplam</span>
                        <span class="fs-5 fw-bold text-primary"><span id="genel-toplam"><?php echo number_format($araToplam, 2); ?></span> TL</span>
                    </div>
                    <a href="ödeme.php" class="btn btn-success w-100 rounded-pill py-3 fw-bold">Ödemeye Geç</a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function sepetGuncelle(id, islem) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('islem', islem);
    formData.append('ajax_islem', '1');

    fetch('sepet.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            if(islem === 'sil' || data.yeni_adet <= 0) {
                document.getElementById('row-' + id).remove();
            } else {
                document.getElementById('adet-' + id).innerText = data.yeni_adet;
                document.getElementById('toplam-' + id).innerText = data.urun_toplam;
            }
            // Genel toplamları güncelle
            document.getElementById('ara-toplam').innerText = data.ara_toplam;
            document.getElementById('genel-toplam').innerText = data.genel_toplam;
            
            // Sepet badge'ını güncelle
            const badge = document.getElementById('cart-badge');
            badge.innerText = data.sepet_toplam;
            if (data.sepet_toplam > 0) {
                badge.style.display = '';
            } else {
                badge.style.display = 'none';
            }
            
            if(data.sepet_bos) {
                document.getElementById('cart-items-container').innerHTML = '<div class="alert alert-info text-center p-5">Sepetiniz şu an boş.</div>';
            }
        }
    });
}
</script>

<?php include_once __DIR__ . '/../parts/footer.php'; ?>