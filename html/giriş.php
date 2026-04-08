<?php 
ob_start(); // EN ÜSTE, BOŞLUKSUZ EKLE
session_start();
include_once __DIR__ . '/../bağlantı.php'; 

$mesaj = "";

// Giriş İşlemi
if (isset($_POST['giriş_yap'])) {
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];

    // 1. Kullanıcıyı e-posta adresinden bulalım
    $sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE email = ?");
    $sorgu->execute([$email]);
    $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

    if ($kullanici) {
        // 2. Şifre kontrolü (Hash'lenmiş şifreyi doğrula)
        if (password_verify($sifre, $kullanici['sifre'])) {
            // Şifre doğru! Oturumu başlatalım
            $_SESSION['user_id'] = $kullanici['id'];
            $_SESSION['user_name'] = $kullanici['ad_soyad'];
            
            header("Location: ../index.php?giriş=basarili");
            exit;
        } else {
            $mesaj = "Hatalı şifre girdiniz!";
        }
    } else {
        $mesaj = "Bu e-posta adresi ile kayıtlı bir kullanıcı bulunamadı!";
    }
}
include_once __DIR__ . '/../parts/header.php'; 
include_once __DIR__ . '/../parts/navbarMenu.php'; 
include_once __DIR__ . '/../parts/banner.php';

?>
<!--giriş-->
<section class="auth-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="auth-card">
                    <h2 class="auth-title text-center mb-4">Hoş Geldin</h2>
                    <?php if(isset($_GET['kayit']) && $_GET['kayit'] == "basarili"): ?>
                        <div class="alert alert-success">Kayıt başarılı! Şimdi giriş yapabilirsiniz.</div>
                    <?php endif; ?>

                    <?php if(!empty($mesaj)): ?>
                        <div class="alert alert-danger"><?php echo $mesaj; ?></div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">E-posta</label>
                            <input type="email" name="email" class="form-control" placeholder="E-posta adresiniz" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Şifre</label>
                            <input type="password" name="sifre" class="form-control" placeholder="••••••••" required>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember">
                                <label class="form-check-label" for="remember">Beni Hatırla</label>
                            </div>
                            <a href="#" class="forgot-pass">Şifremi Unuttum</a>
                        </div>
                        <button type="submit" name="giriş_yap" class="btn btn-auth w-100">Giriş Yap</button>
                    </form>
                    <div class="text-center mt-3">
                        <p class="auth-footer">Hesabın yok mu? <a href="uye_ol.php">Hemen Kayıt Ol</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--giriş-->

<?php include_once __DIR__ . '/../parts/footer.php'; ?>