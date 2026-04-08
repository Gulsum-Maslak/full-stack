-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 08 Nis 2026, 14:16:03
-- Sunucu sürümü: 9.1.0
-- PHP Sürümü: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `coffeeworld-4`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bannerlar`
--

DROP TABLE IF EXISTS `bannerlar`;
CREATE TABLE IF NOT EXISTS `bannerlar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sayfa_key` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `baslik` varchar(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `alt_baslik` varchar(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `resim_yolu` varchar(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `bannerlar`
--

INSERT INTO `bannerlar` (`id`, `sayfa_key`, `baslik`, `alt_baslik`, `resim_yolu`, `aktif`) VALUES
(1, 'index.php', 'Sanatın Kavrulmuş Hali', 'Her çekirdeğin bir hikayesi, her fincanın bir ritüeli vardır. Roast & Ritual ile gerçek kahve deneyimine yolculuk yapın.', 'img/bannerAnasayfa.jpg', 1),
(2, 'kahvelerimiz.php', 'KAHVELERİMİZ', NULL, 'img/bannerSayfalar.jpg', 1),
(3, 'subelerimiz.php', 'ŞUBELERİMİZ', NULL, 'img/bannerSayfalar.jpg', 1),
(4, 'hakkımızda.php', 'HAKKIMIZDA', NULL, 'img/bannerSayfalar.jpg', 1),
(5, 'iletişim.php', 'İLETİŞİM', NULL, 'img/bannerSayfalar.jpg', 1),
(6, 'sepet.php', 'SEPET', '', 'img/bannerSayfalar.jpg', 1),
(7, 'ödeme.php', 'ÖDEME', NULL, 'img/bannerSayfalar.jpg', 1),
(8, 'giriş.php', 'GİRİŞ', NULL, 'img/bannerSayfalar.jpg', 1),
(9, 'uye_ol.php', 'ÜYE OL', NULL, 'img/bannerSayfalar.jpg', 1),
(34, 'deneme.php', 'deneme', NULL, 'img/bannerSayfalar.jpg', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `footer`
--

DROP TABLE IF EXISTS `footer`;
CREATE TABLE IF NOT EXISTS `footer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `copyright_text` varchar(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `linkedin` varchar(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `footer`
--

INSERT INTO `footer` (`id`, `copyright_text`, `facebook`, `twitter`, `instagram`, `linkedin`) VALUES
(1, '© 2024 Kahve Dünyası. Tüm hakları saklıdır.', 'https://www.google.com/search?q=facebook&oq=f&gs_lcrp=EgZjaHJvbWUqDAgAECMYJxiABBiKBTIMCAAQIxgnGIAEGIoFMhgIARAuGEMYgwEYxwEYsQMY0QMYgAQYigUyBggCEEUYOzIGCAMQRRg7Mg0IBBAuGIMBGLEDGIAEMgYIBRBFGDkyDQgGEAAYgwEYsQMYgAQyDQgHEAAYgwEYsQMYgAQyDQgIEAAYgwEYsQMYgAQyFggJE', 'https://x.com/', 'https://www.google.com/search?q=instagram&sca_esv=bf3d4068b2084840&sxsrf=ANbL-n7u3nRywnBhMe_fbxadpa7YVS-5Fg%3A1772279506698&ei=0taiac6sKq6C7NYP962xsQM&biw=1348&bih=595&ved=0ahUKEwjO3cHTj_ySAxUuAdsEHfdWLDYQ4dUDCBA&uact=5&oq=instagram&gs_lp=Egxnd3Mtd2l6LXNl', 'https://www.linkedin.com/');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hakkimizda`
--

DROP TABLE IF EXISTS `hakkimizda`;
CREATE TABLE IF NOT EXISTS `hakkimizda` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ust_baslik` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `alt_baslik` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `aciklama` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `deneyim` varchar(20) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `cekirdek` varchar(20) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `sube` varchar(20) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `musteri` varchar(20) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `eklenme_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deneyim_sayi` varchar(20) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `deneyim_baslik` varchar(100) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `cekirdek_sayi` varchar(20) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `cekirdek_baslik` varchar(100) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `sube_sayi` varchar(20) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `sube_baslik` varchar(100) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `musteri_sayi` varchar(20) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `musteri_baslik` varchar(100) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `hakkimizda`
--

INSERT INTO `hakkimizda` (`id`, `ust_baslik`, `alt_baslik`, `aciklama`, `deneyim`, `cekirdek`, `sube`, `musteri`, `eklenme_tarihi`, `deneyim_sayi`, `deneyim_baslik`, `cekirdek_sayi`, `cekirdek_baslik`, `sube_sayi`, `sube_baslik`, `musteri_sayi`, `musteri_baslik`) VALUES
(1, 'Hakkımızda', 'Bir Fincan Kahvenin Peşinde 10 Yıl', '2016\'da küçük bir dükkanla başlayan yolculuğumuz, bugün en kaliteli çekirdekleri sizinle buluşturan bir tutkuya dönüştü.', '10+', '25+', '12+', '500K+', '2026-02-28 10:45:54', '10+', 'Yıllık Deneyim', '25+', 'Çekirdek Çeşidi', '12+', 'Şehirde Şube', '500K+', 'Mutlu Kahvesever');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `iletisim_bilgileri`
--

DROP TABLE IF EXISTS `iletisim_bilgileri`;
CREATE TABLE IF NOT EXISTS `iletisim_bilgileri` (
  `id` int NOT NULL AUTO_INCREMENT,
  `adres` text COLLATE utf8mb4_turkish_ci,
  `telefon` varchar(50) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `harita_link` text COLLATE utf8mb4_turkish_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `iletisim_bilgileri`
--

INSERT INTO `iletisim_bilgileri` (`id`, `adres`, `telefon`, `email`, `harita_link`) VALUES
(1, 'Kahve Sokak No:12, Beşiktaş/İstanbul', '+90 212 555 00 00', 'merhaba@kahvedukkani.com', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d385396.3211772145!2d28.68251647219845!3d41.\r\n0053701962414!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.\r\n1!3m3!1m2!1s0x14caa7040068086b%3A0xe1ccfe98bc01b0d0!2zxLBzdGFuYnVs!5e0!3m2!1str!2str!4v1770547839616!5m2!1str!2str');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `iletisim_mesajlari`
--

DROP TABLE IF EXISTS `iletisim_mesajlari`;
CREATE TABLE IF NOT EXISTS `iletisim_mesajlari` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ad` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_turkish_ci NOT NULL,
  `konu` varchar(200) COLLATE utf8mb4_turkish_ci NOT NULL,
  `mesaj` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `gonderim_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `iletisim_mesajlari`
--

INSERT INTO `iletisim_mesajlari` (`id`, `ad`, `email`, `konu`, `mesaj`, `gonderim_tarihi`) VALUES
(2, 'deneme', 'deneme@ggg', 'test', 'cdscfvfgtgfz', '2026-04-06 11:49:45');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kahveler`
--

DROP TABLE IF EXISTS `kahveler`;
CREATE TABLE IF NOT EXISTS `kahveler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `baslik` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `aciklama` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `resim` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `eski_fiyat` decimal(10,2) DEFAULT NULL,
  `yeni_fiyat` decimal(10,2) NOT NULL,
  `aktif` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `kahveler`
--

INSERT INTO `kahveler` (`id`, `baslik`, `aciklama`, `resim`, `eski_fiyat`, `yeni_fiyat`, `aktif`) VALUES
(1, 'Americano', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Animi, delectus.', 'img/kart1.jpg', 100.00, 50.00, 1),
(2, 'türk kahvesi', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Animi, delectus.', 'img/kart2.jpg', 100.00, 70.00, 1),
(3, 'Cappuccino', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Soluta natus quod optio commodi deleniti quidem.', 'img/kart3.jpg', 100.00, 90.00, 1),
(4, 'Latte', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque repudiandae voluptas repellat consequuntur iusto, officia ratione!', 'img/kart4.jpg', 100.00, 90.00, 1),
(5, 'Mocha', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis, reiciendis?', 'img/kart5.jpg', 200.00, 100.00, 1),
(6, 'Flat White', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut, quis?', 'img/kart6.jpg', 100.00, 70.00, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

DROP TABLE IF EXISTS `kullanicilar`;
CREATE TABLE IF NOT EXISTS `kullanicilar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ad_soyad` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `sifre` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `rol` enum('user','admin') COLLATE utf8mb4_turkish_ci DEFAULT 'user',
  `kayit_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `ad_soyad`, `email`, `sifre`, `rol`, `kayit_tarihi`) VALUES
(1, 'gülsüm maslak', 'msl@gl', '$2y$10$1NWBCTZOhBSQuyGIaK9DHuQzNVZq1oO7jOrohKniiDR.m2V.9rm/e', 'user', '2026-03-07 17:06:49');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `menuler`
--

DROP TABLE IF EXISTS `menuler`;
CREATE TABLE IF NOT EXISTS `menuler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu_ad` varchar(50) NOT NULL,
  `menu_url` varchar(100) NOT NULL,
  `sira` int DEFAULT '0',
  `aktif` tinyint(1) DEFAULT '1',
  `icerik` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `menuler`
--

INSERT INTO `menuler` (`id`, `menu_ad`, `menu_url`, `sira`, `aktif`, `icerik`) VALUES
(1, 'Anasayfa', 'index.php', 1, 1, NULL),
(2, 'Kahvelerimiz', 'kahvelerimiz.php', 2, 1, NULL),
(3, 'Şubelerimiz', 'subelerimiz.php', 3, 1, NULL),
(4, 'Hakkımızda', 'hakkımızda.php', 4, 1, NULL),
(5, 'İletişim', 'iletişim.php', 5, 1, NULL);
INSERT INTO `menuler` (`id`, `menu_ad`, `menu_url`, `sira`, `aktif`, `icerik`) VALUES
(56, 'deneme', 'deneme.php', 6, 1, '<h1 style=\"text-align: center;\"><span style=\"background-color: rgb(255, 255, 0);\" arial=\"\" black\";\"=\"\">Lorem Ipsum</span></h1>\r\n\r\n\r\n\r\n<p><img src=\"data:image/png;base64,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\" style=\"width:200px\">Yinelenen bir sayfa içeriğinin okuyucunun dikkatini dağıttığı bilinen bir gerçektir. Lorem Ipsum kullanmanın amacı, sürekli \'buraya metin gelecek, buraya metin gelecek\' yazmaya kıyasla daha dengeli bir harf dağılımı sağlayarak okunurluğu artırmasıdır. Şu anda birçok masaüstü yayıncılık paketi ve web sayfa düzenleyicisi, varsayılan mıgır metinler olarak Lorem Ipsum kullanmaktadır. Ayrıca arama motorlarında \'lorem ipsum\' anahtar sözcükleri ile arama yapıldığında henüz tasarım aşamasında olan çok sayıda site listelenir. Yıllar içinde, bazen kazara, bazen bilinçli olarak (örneğin mizah katılarak), çeşitli sürümleri geliştirilmiştir.</p>\r\n');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `odeme_ayarlari`
--

DROP TABLE IF EXISTS `odeme_ayarlari`;
CREATE TABLE IF NOT EXISTS `odeme_ayarlari` (
  `id` int NOT NULL AUTO_INCREMENT,
  `yontem_key` varchar(50) DEFAULT NULL,
  `durum` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `yontem_key` (`yontem_key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `odeme_ayarlari`
--

INSERT INTO `odeme_ayarlari` (`id`, `yontem_key`, `durum`) VALUES
(1, 'kapida_odeme', 1),
(2, 'kredi_karti', 1),
(3, 'eft_havale', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sepet`
--

DROP TABLE IF EXISTS `sepet`;
CREATE TABLE IF NOT EXISTS `sepet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kullanici_id` int NOT NULL,
  `urun_ad` varchar(255) NOT NULL,
  `urun_fiyat` decimal(10,2) NOT NULL,
  `urun_resim` varchar(255) NOT NULL,
  `adet` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparisler`
--

DROP TABLE IF EXISTS `siparisler`;
CREATE TABLE IF NOT EXISTS `siparisler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kullanici_id` int NOT NULL,
  `teslimat_adres` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `telefon` varchar(20) COLLATE utf8mb4_turkish_ci NOT NULL,
  `odeme_yontemi` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `ara_toplam` decimal(10,2) NOT NULL,
  `kargo` decimal(10,2) NOT NULL,
  `toplam` decimal(10,2) NOT NULL,
  `durum` varchar(50) COLLATE utf8mb4_turkish_ci DEFAULT 'Bekliyor',
  `kayit_tarihi` datetime DEFAULT CURRENT_TIMESTAMP,
  `tarih` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `siparisler`
--

INSERT INTO `siparisler` (`id`, `kullanici_id`, `teslimat_adres`, `telefon`, `odeme_yontemi`, `ara_toplam`, `kargo`, `toplam`, `durum`, `kayit_tarihi`, `tarih`) VALUES
(1, 2, 'Antalya Manav', '053976444', 'Kapıda Ödeme', 70.00, 20.00, 90.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-08 16:45:22'),
(2, 2, 'Antalya ', '0539762222', 'Kapıda Ödeme', 550.00, 20.00, 570.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-08 17:06:20'),
(3, 1, 'Ant', '05777777', 'Kapıda Ödeme', 100.00, 20.00, 120.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-09 15:20:50'),
(4, 1, 'f', 'f', 'Kapıda Ödeme', 100.00, 20.00, 120.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-09 15:23:49'),
(5, 1, 'd', 'd', 'Kapıda Ödeme', 70.00, 20.00, 90.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-09 15:30:51'),
(6, 1, 'ssss', '05', 'Kapıda Ödeme', 140.00, 20.00, 160.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-09 15:32:54'),
(7, 1, 'x', 'x', 'Kapıda Ödeme', 100.00, 20.00, 120.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-09 15:38:02'),
(8, 1, 'f f Taf', 'f', 'Kapıda Ödeme', 100.00, 20.00, 120.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-09 15:43:51'),
(9, 1, 'a', 's', 'Kapıda Ödeme', 70.00, 20.00, 90.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-09 15:45:35'),
(10, 1, 'Antalya Man', 's', 'Kapıda Ödeme', 70.00, 20.00, 90.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-09 15:48:32'),
(11, 1, 'Antalya Mana', '77', 'Kapıda Ödeme', 100.00, 20.00, 120.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-09 15:55:43'),
(12, 1, 'kkk', '1111', 'Kapıda Ödeme', 100.00, 20.00, 120.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-10 23:03:22'),
(13, 1, 'snsss', 's', 'Kapıda Ödeme', 100.00, 20.00, 120.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-10 23:14:19'),
(14, 1, 'dd', 'ww', 'Kapıda Ödeme', 100.00, 20.00, 120.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-10 23:15:58'),
(15, 1, 'Aiii', '22', 'Kapıda Ödeme', 50.00, 20.00, 70.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-10 23:21:22'),
(16, 1, 'Anllllllllllllllllll', '11', 'Kapıda Ödeme', 100.00, 20.00, 120.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-10 23:27:38'),
(17, 1, 'Antaşşşşşşşşş', '000', 'Kredi Kartı', 50.00, 20.00, 70.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-12 19:44:34'),
(18, 1, 'kkkkkkkkkkkk', '00000', 'Kredi Kartı', 70.00, 20.00, 90.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-12 19:55:36'),
(19, 1, 's', 's', 'Kapıda Ödeme', 90.00, 20.00, 110.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-13 13:48:34'),
(20, 1, 'd', 'd', 'Kapıda Ödeme', 100.00, 20.00, 120.00, 'Bekliyor', '2026-04-06 15:50:20', '2026-03-13 13:49:56'),
(21, 1, 'ccccccccccccccccc', '0500000', 'Kapıda Ödeme', 170.00, 20.00, 190.00, 'Bekliyor', '2026-04-06 16:01:03', '2026-04-06 16:01:03'),
(22, 1, 's', 's', 'Kapıda Ödeme', 100.00, 20.00, 120.00, 'Bekliyor', '2026-04-07 15:00:07', '2026-04-07 15:00:07'),
(23, 1, 'd', 'd', 'Kapıda Ödeme', 270.00, 20.00, 290.00, 'Bekliyor', '2026-04-08 16:04:43', '2026-04-08 16:04:43');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparis_detay`
--

DROP TABLE IF EXISTS `siparis_detay`;
CREATE TABLE IF NOT EXISTS `siparis_detay` (
  `id` int NOT NULL AUTO_INCREMENT,
  `siparis_id` int NOT NULL,
  `urun_ad` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `urun_fiyat` decimal(10,2) NOT NULL,
  `adet` int NOT NULL,
  `urun_resim` varchar(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `siparis_id` (`siparis_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `siparis_detay`
--

INSERT INTO `siparis_detay` (`id`, `siparis_id`, `urun_ad`, `urun_fiyat`, `adet`, `urun_resim`) VALUES
(1, 1, 'Flat White', 70.00, 1, 'img/kart6.jpg'),
(2, 2, 'Latte', 90.00, 1, 'img/kart4.jpg'),
(3, 2, 'Mocha', 100.00, 3, 'img/kart5.jpg'),
(4, 2, 'Cappuccino', 90.00, 1, 'img/kart3.jpg'),
(5, 2, 'Flat White', 70.00, 1, 'img/kart6.jpg'),
(6, 3, 'Mocha', 100.00, 1, 'img/kart5.jpg'),
(7, 4, 'Mocha', 100.00, 1, 'img/kart5.jpg'),
(8, 5, 'türk kahvesi', 70.00, 1, 'img/kart2.jpg'),
(9, 6, 'türk kahvesi', 70.00, 2, 'img/kart2.jpg'),
(10, 7, 'Mocha', 100.00, 1, 'img/kart5.jpg'),
(11, 8, 'Mocha', 100.00, 1, 'img/kart5.jpg'),
(12, 9, 'Flat White', 70.00, 1, 'img/kart6.jpg'),
(13, 10, 'Flat White', 70.00, 1, 'img/kart6.jpg'),
(14, 11, 'Mocha', 100.00, 1, 'img/kart5.jpg'),
(15, 12, 'Mocha', 100.00, 1, 'img/kart5.jpg'),
(16, 13, 'Mocha', 100.00, 1, 'img/kart5.jpg'),
(17, 14, 'Mocha', 100.00, 1, 'img/kart5.jpg'),
(18, 15, 'Americano', 50.00, 1, 'img/kart1.jpg'),
(19, 16, 'Mocha', 100.00, 1, 'img/kart5.jpg'),
(20, 17, 'Americano', 50.00, 1, 'img/kart1.jpg'),
(21, 18, 'Flat White', 70.00, 1, 'img/kart6.jpg'),
(22, 19, 'Cappuccino', 90.00, 1, 'img/kart3.jpg'),
(23, 20, 'Mocha', 100.00, 1, 'img/kart5.jpg'),
(24, 21, 'Mocha', 100.00, 1, 'img/kart5.jpg'),
(25, 21, 'Flat White', 70.00, 1, 'img/kart6.jpg'),
(26, 22, 'Mocha', 100.00, 1, 'img/kart5.jpg'),
(27, 23, 'Latte', 90.00, 2, 'img/kart4.jpg'),
(28, 23, 'Cappuccino', 90.00, 1, 'img/kart3.jpg');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `öne_çıkan`
--

DROP TABLE IF EXISTS `öne_çıkan`;
CREATE TABLE IF NOT EXISTS `öne_çıkan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `baslik` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `aciklama` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `resim` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `fiyat_eski` decimal(10,2) NOT NULL,
  `fiyat_yeni` decimal(10,2) NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `eklenme_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `öne_çıkan`
--

INSERT INTO `öne_çıkan` (`id`, `baslik`, `aciklama`, `resim`, `fiyat_eski`, `fiyat_yeni`, `link`, `eklenme_tarihi`) VALUES
(1, 'Americano', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Animi, delectus.', 'img/kart1.jpg', 100.00, 50.00, 'html/kahvelerimiz.php', '2026-02-26 18:35:02'),
(2, 'Türk kahvesi', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ullam sed reiciendis maiores!', 'img/kart2.jpg', 100.00, 70.00, 'html/kahvelerimiz.php', '2026-02-26 18:35:02'),
(3, 'Cappuccino', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Soluta natus quod optio commodi deleniti quidem.', 'img/kart3.jpg', 100.00, 90.00, '', '2026-02-26 18:35:02');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `şube`
--

DROP TABLE IF EXISTS `şube`;
CREATE TABLE IF NOT EXISTS `şube` (
  `id` int NOT NULL AUTO_INCREMENT,
  `baslik` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `aciklama` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `resim` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `eklenme_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `şube`
--

INSERT INTO `şube` (`id`, `baslik`, `aciklama`, `resim`, `link`, `eklenme_tarihi`) VALUES
(1, 'İstanbul Şubesi', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum, itaque?', 'img/sube1.jpg', 'html/subelerimiz.php', '2026-02-26 18:53:54'),
(2, 'Ankara Şubesi', 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fugit sapiente nam ut hic, assumenda accusantium!\n\n...\nAnkara Şubesi\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Fugit sapiente nam ut hic, assumenda accusantium!\n\n...\nAnkara Şubesi\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Fugit sapiente nam ut hic, assumenda accusantium!\n\n...\nAnkara Şubesi\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Fugit sapiente nam ut hic, assumenda accusantium!\n\n...\nAnkara Şubesi\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Fugit sapiente nam ut hic, assumenda accusantium!\n\nAnkara Şubesi\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Fugit sapiente nam ut hic, assumenda accusantium!', 'img/sube2.jpg', 'html/subelerimiz.php', '2026-02-26 18:53:54'),
(3, 'Antalya Şubesi', 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Minima vero accusantium necessitatibus reiciendis recusandae.', 'img/sube3.jpg', 'html/subelerimiz.php', '2026-02-26 18:53:54'),
(4, 'İzmir Şubesi\r\n', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ad, eum.', 'img/sube4.jpg', 'html/subelerimiz.php', '2026-02-26 19:00:19');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `şubeler`
--

DROP TABLE IF EXISTS `şubeler`;
CREATE TABLE IF NOT EXISTS `şubeler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `baslik` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `aciklama` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `aktif` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `şubeler`
--

INSERT INTO `şubeler` (`id`, `baslik`, `aciklama`, `img`, `aktif`) VALUES
(1, 'İstanbul Şubesi', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque, consequatur.', 'img/sube1.jpg', 1),
(2, 'Ankara Şubesi', 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sed est nobis amet soluta molestiae? Veniam.', 'img/sube2.jpg', 1),
(3, 'Antalya Şubesi', 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Doloribus perspiciatis magnam deserunt voluptates.', 'img/sube3.jpg', 1),
(4, 'İzmir Şubesi', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Culpa commodi illo ipsum voluptatibus nisi, accusantium voluptas tempore.', 'img/sube4.jpg', 1),
(5, 'Erzurum Şubesi', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Molestiae, reiciendis?', 'img/sube5.jpg', 1),
(6, 'Şanlıurfa Şubesi', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae, distinctio!', 'img/sube6.jpg', 1),
(7, 'Kastamonu Şubesi', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Itaque, labore?', 'img/sube7.jpg', 1),
(8, 'Samsun Şubesi', 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ipsa, quod?', 'img/sube8.jpg', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
