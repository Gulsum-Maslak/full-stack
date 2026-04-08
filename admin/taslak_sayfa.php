<?php 
include_once __DIR__ . '/../bağlantı.php'; 
include_once __DIR__ . '/../parts/header.php'; 
include_once __DIR__ . '/../parts/navbarMenu.php'; 
include_once __DIR__ . '/../parts/banner.php';

// Bu sayfanın adını al (Örn: deneme.php)
$mevcut_url = basename($_SERVER['PHP_SELF']);

// Veritabanından içeriği çek
$sorgu = $db->prepare("SELECT icerik FROM menuler WHERE menu_url = ?");
$sorgu->execute([$mevcut_url]);
$sayfa = $sorgu->fetch(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    <?php 
    // Eğer içerik boşsa uyarı ver, doluysa içeriği bas
    echo !empty($sayfa['icerik']) ? $sayfa['icerik'] : '<p class="text-muted">Sayfa içeriği hazırlanıyor...</p>'; 
    ?>
</div>

<?php include_once __DIR__ . '/../parts/footer.php'; ?>