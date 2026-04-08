<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include_once __DIR__ . '/../bağlantı.php';

if (!isset($_GET['id'])) { header("Location: menuler.php"); exit; }
$id = $_GET['id'];

$sorgu = $db->prepare("SELECT * FROM menuler WHERE id = ?");
$sorgu->execute([$id]);
$menu = $sorgu->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['icerik_kaydet'])) {
    $guncelle = $db->prepare("UPDATE menuler SET icerik = ? WHERE id = ?");
    $guncelle->execute([$_POST['icerik'], $id]);
    header("Location: menuler.php?durum=ok");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İçerik Editörü</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f4f6f9; }
        .editor-card { border-radius: 15px; overflow: hidden; border: none; }
        .note-editor { border-radius: 8px !important; border: 1px solid #dee2e6 !important; }
        .note-toolbar { background: #f8f9fa !important; border-bottom: 1px solid #dee2e6 !important; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card editor-card shadow-lg">
                <div class="card-header bg-dark text-white p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-palette2 me-2 text-warning"></i> Sayfa Tasarımı: <?php echo htmlspecialchars($menu['menu_ad']); ?></h5>
                    <a href="menuler.php" class="btn btn-outline-light btn-sm">Listeye Dön</a>
                </div>
                <div class="card-body bg-white p-4">
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Sayfa Orta Alan İçeriği</label>
                            <textarea id="summernote" name="icerik"><?php echo $menu['icerik']; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <button type="submit" name="icerik_kaydet" class="btn btn-primary btn-lg w-100 shadow">
                                    <i class="bi bi-save2-fill me-2"></i> TASARIMI KAYDET
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="js/adminlte.min.js"></script>
<script src="js/js.js"></script>


</body>
</html>