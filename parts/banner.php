<?php
$mevcutSayfa = basename($_SERVER['PHP_SELF']);
$bannerSorgu = $db->prepare("SELECT * FROM bannerlar WHERE sayfa_key = ? AND aktif = 1");
$bannerSorgu->execute([$mevcutSayfa]);

$banner = $bannerSorgu->fetch(PDO::FETCH_ASSOC);

if (!$banner) return;

// Banner tipini belirle
$bannerClass = "banner-dark"; // varsayılan siyah overlay

if ($mevcutSayfa == "index.php") {
    $bannerClass = "banner-home";
} elseif ($mevcutSayfa == "iletişim.php" || $mevcutSayfa == "subelerimiz.php") {
    $bannerClass = "banner-light";
}
?>

<section class="main-banner <?php echo $bannerClass; ?>" 
    style="background-image: url('<?php echo BASE_URL . $banner['resim_yolu']; ?>');">

    <div class="overlay"></div>

    <div class="container">
        <div class="banner-content">
            <h1><?= htmlspecialchars($banner['baslik']); ?></h1>

            <?php if($mevcutSayfa == "index.php"): ?>
                <p><?php echo $banner['alt_baslik']; ?></p>
            <?php endif; ?>
        </div>
    </div>

</section>