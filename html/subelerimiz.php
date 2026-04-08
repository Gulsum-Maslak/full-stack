<?php 
include_once __DIR__ . '/../parts/header.php'; 
include_once __DIR__ . '/../parts/navbarMenu.php'; 
include_once __DIR__ . '/../parts/banner.php'; 
?>

<!--şubeler start-->
<section class="section-dark py-5">
  <div class="container">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
      <?php
        $sorgu = $db->query("SELECT * FROM şubeler WHERE aktif = 1 ORDER BY id DESC");
        $şubeler = $sorgu->fetchAll(PDO::FETCH_ASSOC);

        foreach($şubeler as $şube):
          $modelID = "şubeModal" . $şube['id'];
      ?>
      <div class="col reveal-card">
        <a href="#">
          <div class="card text-bg-dark" data-bs-toggle="modal" data-bs-target="#<?php echo $modelID; ?>">
          <img src="../<?php echo $şube['img'];?>" class="card-img" alt="...">
          <div class="card-img-overlay">
            <h5 class="card-title"><?php echo $şube['baslik'];?></h5>
            <p class="card-text"><?php echo $şube['aciklama'];?></p>
          </div>
        </div>  
        </a>     
      </div>
      <!--model start-->
        <div class="modal fade" id="<?php echo $modelID; ?>" tabindex="-1" aria-labelledby="subeModal1Label" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content coffee-modal">   
            <div class="modal-header">
                <h5 class="modal-title" id="<?php echo $modelID;?>Label">Şube Detay</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="modal-img-container">
                  <img src="../<?php echo $şube['img'];?>" class="img-fluid rounded" alt="İstanbul Şubesi">
                </div>
                <h2 class="product-title"><?php echo $şube['baslik'];?></h2>
                <p class="product-description">
                  <?php echo $şube['aciklama'];?>
                </p>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-close-modal" data-bs-dismiss="modal">Kapat</button>
              </div>

            </div>
          </div>
        </div>
      <!--model end-->
      <?php endforeach; ?>
  </div>
</section>

<!--şubeler end-->
<?php include_once __DIR__ . '/../parts/footer.php'; ?>