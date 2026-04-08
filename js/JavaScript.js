// --- 1. SEPETE EKLEME İŞLEMİ (Index ve Kahvelerimiz) ---
const SEPET_EKLE_URL = window.location.origin + '/CoffeeWorld-4-backend/html/sepet_islem.php';

function sepeteEkle(buton) {
    const urunId = buton.getAttribute('data-id');
    const source = buton.getAttribute('data-source') || 'kahveler';
    const ilkEklemeYapildi = sessionStorage.getItem('sepeteIlkEklemeYapildi');

    const formData = new FormData();
    formData.append('id', urunId);
    formData.append('islem', 'sepete_ekle');
    formData.append('source', source);

    fetch(SEPET_EKLE_URL, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(yeniAdet => {
        const badge = document.getElementById('cart-badge');
        if (badge) {
            badge.textContent = yeniAdet.trim();
            badge.removeAttribute('style');
            badge.style.display = parseInt(yeniAdet.trim()) > 0 ? 'inline' : 'none';
        }

        if (!ilkEklemeYapildi) {
            sessionStorage.setItem('sepeteIlkEklemeYapildi', '1');
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Fetch Hatası:', error);
    });
}

const searchForm = document.querySelector('form[role="search"]');
if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
        const queryInput = this.querySelector('#searchQuery');
        if (!queryInput || queryInput.value.trim().length === 0) {
            if (queryInput) {
                queryInput.focus();
            }
            e.preventDefault();
            return false;
        }
    });
}

document.addEventListener('click', function(event) {
    const target = event.target.closest('.kahve-ekle-btn, .sepete-ekle-btn');
    if (!target) return;
    event.preventDefault();
    sepeteEkle(target);
});

    // --- 2. MODAL KONTROLÜ (Karta basınca modal, butona basınca sepet) ---
    $('.modal-tetikleyici').on('click', function(e) {
        if (!$(e.target).closest('.kahve-ekle-btn, .sepete-ekle-btn').length) {
            let modalHedef = $(this).data('modal-id');
            if(modalHedef) { 
                let myModal = new bootstrap.Modal(document.querySelector(modalHedef));
                myModal.show();
            }
        }
    });

    // --- 3. SEPET SAYFASI GÜNCELLEMELERİ (Artır, Azalt, Sil) ---
    function sepetIslem(id, islem) {
        $.ajax({
            url: 'sepet.php',
            type: 'POST',
            data: { ajax_islem: 1, id: id, islem: islem },
            success: function(response) {
                location.reload(); // Tasarımı korumak için en güvenli yol
            }
        });
    }

    $(document).on('click', '.btn-arttir', function(e) { e.preventDefault(); sepetIslem($(this).data('id'), 'arttir'); });
    $(document).on('click', '.btn-azalt', function(e) { e.preventDefault(); sepetIslem($(this).data('id'), 'azalt'); });
    $(document).on('click', '.btn-sil', function(e) { 
        e.preventDefault(); 
        if(confirm('Ürünü silmek istiyor musunuz?')) {
            sepetIslem($(this).data('id'), 'sil'); 
        }
    });

    // --- 4. YUKARI ÇIK BUTONU ---
    const backToTopButton = document.querySelector("#backToTop");
    if (backToTopButton) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.style.display = "flex";
            } else {
                backToTopButton.style.display = "none";
            }
        });

        backToTopButton.addEventListener("click", function() {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }

    // --- 5. ÖDEME FORMU KONTROLÜ ---
    const checkoutForm = document.querySelector('.checkout-form');
    const orderSuccess = document.querySelector('.order-success');

    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            // Önce PHP işleminin gerçekleşmesi için e.preventDefault() eklemiyoruz 
            // Ancak boş alan kontrolü yapıyoruz.
            const adSoyad = checkoutForm.querySelector('input[placeholder="Ad Soyad"]')?.value.trim();
            const telefon = checkoutForm.querySelector('input[placeholder="Telefon"]')?.value.trim();
            const adres = checkoutForm.querySelector('textarea[placeholder="Adres"]')?.value.trim();

            if(!adSoyad || !telefon || !adres){
                e.preventDefault();
                alert("Lütfen tüm alanları doldurun!");
                return;
            }

            // Eğer formun PHP ile gönderilmesini istemiyor, sadece görsel mesaj istiyorsan 
            // aşağıdaki iki satırı aktif edip form submitini durdurabilirsin.
            // e.preventDefault();
            // checkoutForm.style.display = "none";
            // orderSuccess.classList.remove("d-none");
        });
    }
