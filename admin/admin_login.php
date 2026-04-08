<?php
ob_start();
session_start();
include_once __DIR__ . '/../bağlantı.php';

$hata = "";

if (isset($_POST['admin_giris'])) {
    $email = trim($_POST['email']);
    $sifre = $_POST['sifre'];

    // Admin tablon yoksa 'kullanicilar' tablosunda 'rol' sütunu olduğunu varsayıyoruz
    // Veya sadece belirli bir e-posta adresine yetki verebilirsin
    $sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE email = ?");
    $sorgu->execute([$email]);
    $admin = $sorgu->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($sifre, $admin['sifre'])) {
        // Giriş başarılı
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_ad'] = $admin['ad_soyad'];
        
        header("Location: index.php");
        exit;
    } else {
        $hata = "Geçersiz e-posta veya şifre!";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CoffeeWorld | Admin Girişi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f4f6f9; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 100%; max-width: 400px; padding: 2rem; border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); background: #fff; }
        .btn-coffee { background: #4b3832; color: white; transition: 0.3s; }
        .btn-coffee:hover { background: #3c2a24; color: #fff; }
        .logo-area { text-align: center; margin-bottom: 1.5rem; }
        .logo-area i { font-size: 3rem; color: #4b3832; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="logo-area">
        <i class="bi bi-cup-hot-fill"></i>
        <h4 class="mt-2 fw-bold">Yönetim Paneli</h4>
        <p class="text-muted small">Lütfen kimlik bilgilerinizi girin</p>
    </div>

    <?php if(!empty($hata)): ?>
        <div class="alert alert-danger py-2 small text-center"><?= $hata ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label small fw-bold">E-posta</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="admin@coffeeworld.com" required>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label small fw-bold">Şifre</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                <input type="password" name="sifre" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label small" for="remember">Beni Hatırla</label>
            </div>
            <a href="sifre_sifirla.php" class="text-decoration-none small text-secondary">Şifremi Unuttum?</a>
        </div>

        <button type="submit" name="admin_giris" class="btn btn-coffee w-100 py-2 fw-bold">
            Giriş Yap <i class="bi bi-arrow-right ms-2"></i>
        </button>
    </form>

    <div class="mt-4 text-center">
        <a href="../index.php" class="text-decoration-none small"><i class="bi bi-house-door"></i> Siteye Dön</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>