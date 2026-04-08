<?php
session_start();
include_once __DIR__ . '/../bağlantı.php';

// Kullanıcı ID varsa sepetini sil
if(isset($_SESSION['user_id'])){
    $uid = $_SESSION['user_id'];
    $db->prepare("DELETE FROM sepet WHERE kullanici_id = ?")->execute([$uid]);
}

session_destroy();
header("Location: ../index.php");
exit;