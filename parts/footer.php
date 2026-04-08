<!--footer start-->
<?php
$stmt = $db->prepare("SELECT * FROM footer LIMIT 1");
$stmt->execute();
$footer = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<footer class="text-center border-top border-white py-4">
  <div class="container footer-container">
    <p class="mb-0"><?= htmlspecialchars($footer['copyright_text']) ?></p>
    <div class="social-icons mt-3">
      <a href="<?= htmlspecialchars($footer['facebook']) ?>" target="_blank" class="me-3"><i class="bi bi-facebook"></i></a>
      <a href="<?= htmlspecialchars($footer['twitter']) ?>" target="_blank" class="me-3"><i class="bi bi-twitter"></i></a> 
      <a href="<?= htmlspecialchars($footer['instagram']) ?>" target="_blank" class="me-3"><i class="bi bi-instagram"></i></a>
      <a href="<?= htmlspecialchars($footer['linkedin']) ?>" target="_blank" class="me-3"><i class="bi bi-linkedin"></i></a>
    </div>    
  </div>
</footer>    
<!--footer end-->

<!--yukarı çıkma buttonu-->
<button id="backToTop" class="back-to-top">
  <i class="bi bi-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo BASE_URL; ?>js/JavaScript.js?v=20260408"></script>
<script src="<?php echo BASE_URL; ?>js/scrollreveal.js"></script>
</body>
</html>