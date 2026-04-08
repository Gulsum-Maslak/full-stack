<?php 
include_once __DIR__ . '/../bağlantı.php'; 
include_once __DIR__ . '/../parts/header.php'; 
include_once __DIR__ . '/../parts/navbarMenu.php'; 
include_once __DIR__ . '/../parts/banner.php';
$sorgu = $db->prepare("SELECT icerik FROM menuler WHERE menu_url = ?");
$sorgu->execute(['deneme.php']);
$sayfa = $sorgu->fetch(PDO::FETCH_ASSOC);
?>
<div class='container py-5'><?php echo $sayfa['icerik']; ?></div>
<?php include_once __DIR__ . '/../parts/footer.php'; ?>