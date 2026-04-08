document.addEventListener("DOMContentLoaded", function () {
    
    /* --- 1. URL TEMİZLEME --- */
    // İşlem bittikten 100ms sonra URL'deki ?durum=... kısmını siler (Sayfa yenilendiğinde tekrar işlem yapmasın diye)
    if (window.location.search.includes("durum")) {
        setTimeout(function () {
            const url = new URL(window.location);
            url.searchParams.delete("durum");
            window.history.replaceState({}, document.title, url.pathname);
        }, 100);
    }

    /* --- 2. SUMMERNOTE EDİTÖR --- */
    // Sadece #summernote ID'li bir eleman varsa çalıştır (Hata almamak için)
    if (typeof $ !== 'undefined' && $('#summernote').length > 0) {
        $("#summernote").summernote({
            placeholder: "Buraya yazmaya başlayın, resim sürükleyin veya renkleri ayarlayın...",
            tabsize: 2,
            height: 500,
            lang: "tr-TR",
            toolbar: [
                ["style", ["style"]],
                ["font", ["bold", "underline", "clear"]],
                ["fontname", ["fontname"]],
                ["color", ["color"]],
                ["para", ["ul", "ol", "paragraph"]],
                ["table", ["table"]],
                ["insert", ["link", "picture", "video"]],
                ["view", ["fullscreen", "codeview", "help"]],
            ],
        });
    }

    /* --- 3. AJAX DURUM GÜNCELLEME (Aç-Kapa Butonları) --- */
    // Ödeme sayfası veya benzeri yerlerdeki toggle butonları için
    const statusToggles = document.querySelectorAll(".status-toggle");
    if (statusToggles.length > 0) {
        statusToggles.forEach((checkbox) => {
            checkbox.addEventListener("change", function () {
                const key = this.getAttribute("data-key");
                const durum = this.checked ? 1 : 0;

                fetch(`ödeme.php?islem=durum_guncelle&key=${key}&durum=${durum}`)
                    .then((response) => response.json())
                    .then((data) => {
                        if (!data.success) {
                            alert("Güncelleme sırasında hata oluştu!");
                            this.checked = !this.checked; // Hata varsa eski haline döndür
                        }
                    })
                    .catch(err => {
                        console.error("Hata:", err);
                        this.checked = !this.checked;
                    });
            });
        });
    }
});