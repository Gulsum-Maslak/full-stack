<?php 
include_once __DIR__ . '/../parts/header.php'; 
include_once __DIR__ . '/../parts/navbarMenu.php'; 
include_once __DIR__ . '/../parts/banner.php'; 

?>

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

<?php include_once __DIR__ . '/../parts/footer.php'; ?>