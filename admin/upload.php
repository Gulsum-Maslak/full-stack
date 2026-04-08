<?php
// Resimlerin kaydedileceği klasör (Yazma izni olduğundan emin ol)
$uploadDir = 'img/sayfa_resimleri/';
if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }

if (isset($_FILES['upload'])) {
    $file = $_FILES['upload'];
    $fileName = time() . '_' . basename($file['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        $url = 'img/sayfa_resimleri/' . $fileName; // Sitedeki görünme yolu
        $message = "Resim başarıyla yüklendi!";
        
        // CKEditor'e geri bildirim gönder
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction({$_GET['CKEditorFuncNum']}, '$url', '$message');</script>";
    }
}
?>