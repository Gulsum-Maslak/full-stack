<?php
session_start();
include_once __DIR__ . '/../bağlantı.php';

$kullanici_id = $_SESSION['user_id'] ?? 1;

if (isset($_POST['islem']) && $_POST['islem'] === 'sepete_ekle') {
    $urun_id = $_POST['id'];
    $source = $_POST['source'] ?? 'kahveler';

    if ($source === 'onecikan') {
        $sorgu = $db->prepare("SELECT * FROM öne_çıkan WHERE id = ?");
    } else {
        $sorgu = $db->prepare("SELECT * FROM kahveler WHERE id = ?");
    }
    $sorgu->execute([$urun_id]);
    $urun = $sorgu->fetch(PDO::FETCH_ASSOC);

    if ($urun) {
        if ($source === 'onecikan') {
            $urun_ad = $urun['baslik'];
            $urun_fiyat = $urun['fiyat_yeni'];
            $urun_resim = $urun['resim'];
        } else {
            $urun_ad = $urun['baslik'];
            $urun_fiyat = $urun['yeni_fiyat'];
            $urun_resim = $urun['resim'];
        }

        $kontrol = $db->prepare("SELECT id FROM sepet WHERE kullanici_id = ? AND urun_ad = ?");
        $kontrol->execute([$kullanici_id, $urun_ad]);
        $sepet_kaydi = $kontrol->fetch(PDO::FETCH_ASSOC);

        if ($sepet_kaydi) {
            $db->prepare("UPDATE sepet SET adet = adet + 1 WHERE id = ?")->execute([$sepet_kaydi['id']]);
        } else {
            $db->prepare("INSERT INTO sepet (kullanici_id, urun_ad, urun_fiyat, urun_resim, adet) VALUES (?, ?, ?, ?, 1)")
                ->execute([$kullanici_id, $urun_ad, $urun_fiyat, $urun_resim]);
        }
    }

    $sayac = $db->prepare("SELECT SUM(adet) as toplam FROM sepet WHERE kullanici_id = ?");
    $sayac->execute([$kullanici_id]);
    $sonuc = $sayac->fetch(PDO::FETCH_ASSOC);

    ob_clean();
    echo $sonuc['toplam'] ?? 0;
    exit;
}

http_response_code(400);
echo 'Hatalı istek';
exit;
