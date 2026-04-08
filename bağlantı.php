<?php
// Proje yolunu sabit olarak tanımlıyoruz (En güvenli yol budur)
define('BASE_URL', 'http://localhost/CoffeeWorld-4-backend/'); 

$host = "localhost";
$user = "root";
$pass = ""; 
$db_name = "CoffeeWorld-4";

try {
    $db = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $user, $pass);
   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}
?>