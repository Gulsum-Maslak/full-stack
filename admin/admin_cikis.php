<?php
session_start();
session_unset(); // Tüm session değişkenlerini boşaltır
session_destroy(); // Session'ı tamamen yok eder

// Çıkış yaptıktan sonra login sayfasına yönlendir
header("Location: admin_login.php"); 
exit;
?>