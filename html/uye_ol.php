<?php 
ob_start(); // EN ÜSTE, BOŞLUKSUZ EKLE
session_start();
include_once __DIR__ . '/../bağlantı.php'; 

 // Veritabanı bağlantısının ($db) burada tanımlı olduğundan emin ol
 if (isset($_POST['uye_ol'])) {
    $ad_soyad = $_POST['ad_soyad'];
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];
    $sifre_tekrar = $_POST['sifre_tekrar'];
    // 1. Şifrelerin eşleşip eşleşmediğini kontrol et
    if ($sifre !== $sifre_tekrar) {
        $mesaj = "Şifreler birbiriyle uyuşmuyor!";
    } else {
    // 2. Şifreyi güvenli hale getir (Asla düz metin kaydetme!)
    $hashed_sifre = password_hash($sifre, PASSWORD_DEFAULT);
    // 3. Veriyi veritabanına ekle
    $sorgu = $db->prepare("INSERT INTO kullanicilar (ad_soyad, email, sifre) VALUES (?, ?, ?)");
    $ekle = $sorgu->execute([$ad_soyad, $email, $hashed_sifre]);
        if ($ekle) {
        // Kayıt başarılıysa giriş sayfasına yönlendir
        header("Location: giriş.php?kayit=basarili");
            exit;
        } else {
            $mesaj = "Kayıt sırasında bir hata oluştu!";
            }
        }
    }      
    
    
include_once __DIR__ . '/../parts/header.php'; 
include_once __DIR__ . '/../parts/navbarMenu.php'; 
include_once __DIR__ . '/../parts/banner.php'; 
?>
<section class="auth-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="auth-card">
                  
                        <?php if(isset($mesaj)): ?>
                            <div class="alert alert-danger"><?php echo $mesaj; ?></div>
                        <?php endif; ?>

                    <form action="" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Ad Soyad</label>
                            <input type="text" name="ad_soyad" class="form-control" placeholder="Adınız ve Soyadınız" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-posta</label>
                            <input type="email" name="email" class="form-control" placeholder="E-posta adresiniz" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Şifre</label>
                            <input type="password" name="sifre" class="form-control" placeholder="••••••••" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Şifre Tekrar</label>
                            <input type="password" name="sifre_tekrar" class="form-control" placeholder="••••••••" required>
                        </div>
                        <button type="submit" name="uye_ol" class="btn btn-auth w-100">Kayıt Ol</button>
                    </form>
                    <div class="text-center mt-3">
                        <p class="auth-footer">Zaten üye misin? <a href="giriş.php">Giriş Yap</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once __DIR__ . '/../parts/footer.php'; ?>