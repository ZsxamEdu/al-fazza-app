// 2. State & inisialisasi
let cart = JSON.parse(localStorage.getItem('alfazza_cart')) || [];
let posCart = [];

// Helper: Update character counter pada textarea
function updateCharCount(fieldId, maxLength) {
    const field = document.getElementById(fieldId);
    const counter = document.getElementById(fieldId + '-count');
    if (!field || !counter) return;
    const len = field.value.length;
    counter.textContent = len;
    // Ubah warna jadi merah jika mendekati batas (>90%)
    counter.style.color = len >= maxLength * 0.9 ? '#ef4444' : '';
}


document.addEventListener("DOMContentLoaded", () => {
    // === TAMBAHAN KUNCI TANGGAL ===
    // Sesuaikan ID-nya. Kalau untuk Custom Order: 'co_tanggal'. 
    // Kalau untuk Checkout biasa, ganti dengan ID input tanggalmu.
    const inputTanggal = document.getElementById('co_tanggal'); 
    
    if (inputTanggal) {
        // Ambil tanggal hari ini dengan format YYYY-MM-DD (Standar HTML)
        const today = new Date().toISOString().split('T')[0];
        // Set batas minimal kalender ke hari ini
        inputTanggal.setAttribute('min', today);
    }
    // =============================
    if(window.location.pathname.includes('/checkout')) renderCheckoutSummary();
    updateCartUI(); 

    const hamburger = document.getElementById('hamburger-btn');
    const mainNav = document.getElementById('main-nav');
    const navOverlay = document.getElementById('nav-overlay');
    const categoriesDropdown = document.getElementById('categories-dropdown');

    // A. Buka/Tutup Keranjang (Cart Overlay)
    document.getElementById('cart-btn')?.addEventListener('click', () => {
        document.getElementById('cart-sidebar').classList.add('active');
        document.getElementById('cart-overlay').classList.add('active');
        closeNav(); // Otomatis tutup menu mobile kalau sedang buka keranjang
    });

    document.querySelectorAll('#close-cart, #cart-overlay').forEach(el => 
        el.addEventListener('click', () => {
            document.getElementById('cart-sidebar').classList.remove('active');
            document.getElementById('cart-overlay').classList.remove('active');
        })
    );

    document.querySelector('.btn-checkout')?.addEventListener('click', () => {
        if(cart.length === 0) return Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Keranjang belanja kosong!' });
        window.location.href = '/checkout';
    });

    // B. Logika Hamburger Menu (Mobile)
    function openNav() {
        hamburger?.classList.add('active');
        mainNav?.classList.add('open');
        navOverlay?.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeNav() {
        hamburger?.classList.remove('active');
        mainNav?.classList.remove('open');
        navOverlay?.classList.remove('active');
        document.body.style.overflow = '';
        const ul = categoriesDropdown?.querySelector('.dropdown-menu');
        if(ul) ul.classList.remove('open');
    }

    hamburger?.addEventListener('click', () => {
        mainNav?.classList.contains('open') ? closeNav() : openNav();
    });

    navOverlay?.addEventListener('click', closeNav);

    // C. Dropdown Menu Mobile
    categoriesDropdown?.addEventListener('click', (e) => {
        // Cek apakah hamburger sedang tampil (artinya sedang di mode mobile/tablet)
        if(window.getComputedStyle(hamburger).display !== 'none') {
            // Cek jika yang diklik adalah tulisan 'Kategori' (tag a)
            if(e.target.tagName.toLowerCase() === 'a' && e.target.getAttribute('href') === '#') {
                e.preventDefault();
                const ul = categoriesDropdown.querySelector('.dropdown-menu');
                if(ul) ul.classList.toggle('open');
            }
        }
    });

    // C. Dropdown Kategori di Mobile
    categoriesDropdown?.querySelector('.dropbtn')?.addEventListener('click', (e) => {
        if (window.innerWidth <= 768) {
            e.preventDefault();
            categoriesDropdown.classList.toggle('open');
        }
    });

    mainNav?.querySelectorAll('.nav-links li:not(.dropdown) a, .dropdown-menu a, #cart-btn').forEach(link => {
        link.addEventListener('click', closeNav);
    });

    // D. Chart Pendapatan di Dashboard Admin
    const canvasChart = document.getElementById('revenueChart');
    
    if (canvasChart) {
        const ctx = canvasChart.getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                // Mengambil data jembatan dari window (Blade)
                labels: window.chartLabels, 
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: window.chartData, 
                    borderColor: '#a67c52',
                    backgroundColor: 'rgba(166, 124, 82, 0.2)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#4a3b32',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // E. Render Kasir
    const searchInput = document.getElementById('pos-search-input');
    if (searchInput) {
        // Logika Live Search
        searchInput.addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let cards = document.querySelectorAll('.pos-card');
            
            cards.forEach(card => {
                let title = card.querySelector('.pos-card-title').textContent.toLowerCase();
                // Tampilkan jika cocok, sembunyikan jika tidak
                if (title.includes(filter)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        
        // Panggil render pertama kali agar tulisan "Belum ada pesanan" muncul
        renderPosCart();
    }
});

// 3. Fungsi Keranjang Utama
function saveCart() {
    localStorage.setItem('alfazza_cart', JSON.stringify(cart));
    updateCartUI();
}

function addToCart(id, nama, harga, gambar, qty = 1, stok = 9999) {
    let item = cart.find(i => i.id === id);
    const currentQty = item ? item.quantity : 0;

    // Validasi stok: total qty di keranjang tidak boleh melebihi stok
    if (currentQty + qty > stok) {
        Swal.fire({ 
            toast: true,
            position: 'top-end',
            icon: 'warning', 
            title: 'Stok Tidak Cukup!', 
            text: `Sisa stok ${nama} hanya ${stok} pcs.`, 
            showConfirmButton: false, 
            timer: 2000 
        });
        return;
    }

    if (item) {
        item.quantity += qty;
        item.stok = stok; // Simpan info stok
    } else {
        cart.push({ id: id, name: nama, price: harga, quantity: qty, image: gambar, stok: stok });
    }
    
    saveCart();
    Swal.fire({ 
        toast: true,
        position: 'top-end',
        icon: 'success', 
        title: 'Berhasil!', 
        text: `${qty} ${nama} berhasil ditambahkan!`, 
        showConfirmButton: false, 
        timer: 1500 
    });
}

function removeFromCart(index) {
    // Jika qty 1 dan ditekan minus, tanyakan apakah mau dihapus
    Swal.fire({
        title: 'Yakin ingin menghapus pesanan?',
        html: 'pesanan yang kamu hapus masih dapat ditambahkan di menu kok',
        iconHtml: '<i class="fa-solid fa-trash-can" style="color: var(--color-dark-brown);"></i>',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#a67c52',
        confirmButtonText: 'Hapus Sekarang',
        cancelButtonText: 'Batalkan'
    }).then((result) => {
        if (result.isConfirmed) {
            cart.splice(index, 1);
            localStorage.setItem('alfazza_cart', JSON.stringify(cart));
            updateCartUI();
        }
    });
}

function updateCartUI() {
    const elCount = document.getElementById('cart-count');
    const elItems = document.querySelector('#cart-items');
    const elTotal = document.querySelector('#cart-total');    
    
    if (elCount) elCount.textContent = cart.reduce((tot, item) => tot + item.quantity, 0);

    if (elItems) {
        let grandTotal = 0;
        elItems.innerHTML = cart.map((item, index) => {
            let sub = item.price * item.quantity;
            grandTotal += sub;
    // GANTI MENJADI SEPERTI INI:
            return `
                <div class="flex items-center mb-4 relative border-b border-border-light border-dashed pb-4">
                    
                    <a href="/produk/${item.id}" class="flex items-center gap-4 w-[60%] no-underline text-inherit">
                        <img src="${item.image}" alt="${item.name}" class="w-15 h-15 object-cover rounded-lg">
                        <div>
                            <h4 class="m-0 text-base">${item.name}</h4>
                            <p class="m-0 mt-1 text-primary-brown font-bold">Rp ${item.price.toLocaleString('id-ID')}</p>
                        </div>
                    </a>

                    <div class="flex items-center gap-2.5 ml-auto mr-8">
                        <button type="button" onclick="kurangiQty(${index})" class="w-6 h-6 border border-border-dark bg-white rounded cursor-pointer flex justify-center items-center hover:bg-gray-100">-</button>
                        <input type="number" min="1" max="${item.stok || 9999}" value="${item.quantity}" oninput="if(this.value !== ''){ if(parseInt(this.value) > parseInt(this.max)) this.value = this.max; if(parseInt(this.value) < 1) this.value = 1; }" onchange="setCartQty(${index}, this.value)" class="w-10 h-7 text-center border border-border-dark rounded text-sm font-bold outline-none focus:border-primary-brown">
                        <button type="button" onclick="tambahQty(${index})" class="w-6 h-6 border border-border-dark bg-white rounded cursor-pointer flex justify-center items-center hover:bg-gray-100">+</button>
                    </div>

                    <button type="button" onclick="removeFromCart(${index})" class="absolute right-0 bg-transparent border-none text-danger cursor-pointer text-[1.1rem] hover:text-red-700">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            `;
        }).join('');
        if (elTotal) elTotal.textContent = `Rp ${grandTotal.toLocaleString('id-ID')}`;
    }
}

// Fungsi tombol +/- di halaman detail (dengan validasi stok)
function changeQty(amount) {
    const qtyInput = document.getElementById('qty');
    if (!qtyInput) return;
    const maxStok = parseInt(qtyInput.getAttribute('max')) || 9999;
    const newVal = parseInt(qtyInput.value) + amount;
    if (newVal >= 1 && newVal <= maxStok) {
        qtyInput.value = newVal;
    } else if (newVal > maxStok) {
        Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Batas Stok!', text: `Stok tersedia hanya ${maxStok} pcs.`, showConfirmButton: false, timer: 2000 });
    }
}

// 5. Checkout
function renderCheckoutSummary() {
    const list = document.getElementById('checkout-order-list');
    if (!list) return;

    if (cart.length === 0) {
        Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Keranjang belanja kosong!' });
        window.location.href = '/';
        return;
    }

    let grandTotal = 0;
    list.innerHTML = cart.map(item => {
        let sub = item.price * item.quantity;
        grandTotal += sub;
        return `<div class="order-item" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>${item.quantity}x ${item.name}</span><span>Rp ${sub.toLocaleString('id-ID')}</span>
                </div>`;
    }).join('');

    document.getElementById('checkout-subtotal').textContent = `Rp ${grandTotal.toLocaleString('id-ID')}`;
    document.getElementById('checkout-grandtotal').textContent = `Rp ${grandTotal.toLocaleString('id-ID')}`;
}



// Fungsi untuk menambah Qty di keranjang (dengan validasi stok)
function tambahQty(index) {
    const maxStok = cart[index].stok || 9999;
    if (cart[index].quantity >= maxStok) {
        Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Stok Habis!', text: `Sisa stok hanya ${maxStok} pcs.`, showConfirmButton: false, timer: 2000 });
        return;
    }
    cart[index].quantity += 1;
    localStorage.setItem('alfazza_cart', JSON.stringify(cart));
    updateCartUI();
}

// Fungsi untuk mengurangi Qty di keranjang
function kurangiQty(index) {
    if (cart[index].quantity > 1) {
        cart[index].quantity -= 1;
        localStorage.setItem('alfazza_cart', JSON.stringify(cart));
        updateCartUI();
    } else {
        removeFromCart(index);
    }
}

// Fungsi input ketik langsung untuk qty di keranjang user
function setCartQty(index, value) {
    const newQty = parseInt(value);
    const maxStok = cart[index].stok || 50;
    if (isNaN(newQty) || newQty < 1) {
        cart[index].quantity = 1;
    } else if (newQty > maxStok) {
        Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Melebihi Stok!', text: `Stok tersedia hanya ${maxStok} pcs.`, showConfirmButton: false, timer: 2000 });
        cart[index].quantity = maxStok;
    } else {
        cart[index].quantity = newQty;
    }
    localStorage.setItem('alfazza_cart', JSON.stringify(cart));
    updateCartUI();
}

// 6. Fungsi Custom Order

// Fungsi untuk memunculkan/menyembunyikan field alamat pengiriman
function toggleAlamatCustom() {
    const metode = document.getElementById('co_metode').value;
    const alamatGroup = document.getElementById('co_alamat_group');
    if (metode === 'Dikirim') {
        alamatGroup.style.display = 'block';
    } else {
        alamatGroup.style.display = 'none';
    }
}

// Fungsi Checkout Custom Order via Midtrans
function prosesCustomOrderMidtrans() {
    // 1. Ambil data pemesan
    const nama = document.getElementById('co_nama').value;
    const nohp = document.getElementById('co_nohp').value;
    const email = document.getElementById('co_email')?.value; 
    
    const ukuran = document.getElementById('co_ukuran').value;
    const bentuk = document.getElementById('co_bentuk').value;
    const rasa = document.getElementById('co_rasa').value;
    const isian = document.getElementById('co_isian').value;
    const tema = document.getElementById('co_tema').value;
    const tulisan = document.getElementById('co_tulisan').value;
    const tanggal = document.getElementById('co_tanggal').value;
    const metode = document.getElementById('co_metode').value;
    const alamat = document.getElementById('co_alamat').value;
    const catatan = document.getElementById('co_catatan').value || "-";

    // 2. Validasi (PALANG PINTU)
    if (!nama || nama.trim() === "") {
        Swal.fire({ icon: 'warning', text: 'Mohon lengkapi Nama Anda!' });
        document.getElementById('co_nama').focus();
        return; 
    }

    const regexHurufCustom = /^[A-Za-z\s]+$/;
    if (!regexHurufCustom.test(nama)) {
        Swal.fire({ icon: 'warning', text: 'Nama hanya boleh berisi huruf dan spasi (tanpa angka/simbol)!' });
        document.getElementById('co_nama').focus();
        return;
    }

    if (!email || email.trim() === "") {
        Swal.fire({ icon: 'warning', text: 'Mohon isi Email Anda!' });
        document.getElementById('co_email').focus();
        return;
    }

    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        Swal.fire({ icon: 'warning', text: 'Format Email tidak valid! (Contoh: nama@email.com)' });
        document.getElementById('co_email').focus();
        return;
    }

    if (!nohp || nohp.trim() === "") {
        Swal.fire({ icon: 'warning', text: 'Mohon isi No WhatsApp!' });
        document.getElementById('co_nohp').focus();
        return; 
    } else if (nohp.length > 15) { 
        Swal.fire({ icon: 'warning', text: 'No WhatsApp tidak boleh lebih dari 15 karakter!' });
        document.getElementById('co_nohp').focus();
        return; 
    }

    // === DETEKTOR SPESIFIKASI KUE (Wajib Pilih) ===
    if (!ukuran || ukuran === "") {
        Swal.fire({ icon: 'warning', text: 'Mohon pilih Ukuran Kue terlebih dahulu!' });
        document.getElementById('co_ukuran').focus();
        return; 
    }
    
    if (!bentuk || bentuk === "") {
        Swal.fire({ icon: 'warning', text: 'Mohon pilih Bentuk Kue!' });
        document.getElementById('co_bentuk').focus();
        return; 
    }

    if (!rasa || rasa === "") {
        Swal.fire({ icon: 'warning', text: 'Mohon pilih Base Cake (Rasa)!' });
        document.getElementById('co_rasa').focus();
        return; 
    }

    if (!isian || isian === "") {
        Swal.fire({ icon: 'warning', text: 'Mohon pilih Filling / Isian!' });
        document.getElementById('co_isian').focus();
        return; 
    }

    if (!tema || tema.trim() === "") {
        Swal.fire({ icon: 'warning', text: 'Mohon isi Tema/Warna kue!' });
        document.getElementById('co_tema').focus();
        return; 
    } else if (tema.length > 70) { // <-- Ini kode tambahannya
        Swal.fire({ icon: 'warning', text: 'Tema dan warna kue maksimal 70 karakter!' });
        document.getElementById('co_tema').focus();
        return; 
    }


    if (!tulisan || tulisan.trim() === "") {
        Swal.fire({ icon: 'warning', text: 'Mohon isi Tulisan di atas kue' });
        document.getElementById('co_tulisan').focus();
        return; 
    } else if (tulisan.length > 25) { // <-- Ini kode tambahannya
        Swal.fire({ icon: 'warning', text: 'Tulisan di atas kue maksimal 25 karakter!' });
        document.getElementById('co_tulisan').focus();
        return; 
    }

    if (!tanggal || tanggal.trim() === "") {
        Swal.fire({ icon: 'warning', text: 'Mohon isi Tanggal Pengiriman terlebih dahulu!' });
        document.getElementById('co_tanggal').focus();
        return; // Menghentikan script ke Midtrans
    }

    // === SATPAM ANTI TANGGAL MASA LALU (MANUAL KETIK) ===
    const hariIni = new Date().toISOString().split('T')[0]; // Ambil tgl hari ini (YYYY-MM-DD)
    if (tanggal < hariIni) {
        Swal.fire({ icon: 'warning', text: 'Tanggal pengiriman tidak boleh berlalu (kurang dari hari ini)!' });
        document.getElementById('co_tanggal').value = ""; // Kosongkan inputan yang salah
        document.getElementById('co_tanggal').focus();
        return; // Hentikan proses!
    }

    if (!metode || metode.trim() === "") {
        Swal.fire({ icon: 'warning', text: 'Mohon isi Metode pengiriman anda' });
        document.getElementById('co_metode').focus();
        return; 
    }

    if (metode === "Dikirim" && (!alamat || alamat.trim() === "")) {
        Swal.fire({ icon: 'warning', text: 'Mohon isi detail alamat pengiriman!' });
        document.getElementById('co_alamat').focus();
        return; 
    } else if (alamat.length > 100) { // <-- Ini kode tambahannya
        Swal.fire({ icon: 'warning', text: 'alamat maksimal 100 karakter!' });
        document.getElementById('co_alamat').focus();
        return; 
    }

    if (catatan.length > 250) {
        Swal.fire({ icon: 'warning', text: 'Catatan tambahan maksimal 250 karakter!' });
        document.getElementById('co_catatan').focus();
        return; 
    }

    // === TAMBAHAN GERBANG KONFIRMASI ===
    Swal.fire({
        title: 'Konfirmasi Pesanan?',
        text: "Sebelum lakukan pesanan pastikan data diri dan spesifikasi sudah sesuai ya",
        iconHtml: '<i class="fa-solid fa-cart-shopping" style="color: var(--color-dark-brown);"></i>',
        showCancelButton: true,
        confirmButtonColor: '#a67c52',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya. saya yakin',
        cancelButtonText: 'Kembali'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoader();
    // 3. Tentukan Harga (Karena Midtrans WAJIB ada angka tagihan)
    // Kamu bisa ganti angka ini, atau ambil dari inputan harga jika ada
    let hargaCustomCake = 150000; 

    if (ukuran.includes('18')) {
        hargaCustomCake = 180000; // Jika ukuran 18 cm, harga 180k
    } else if (ukuran.includes('20')) {
        hargaCustomCake = 220000; // Jika ukuran 20 cm, harga 220k
    } else if (ukuran.includes('22')) {
        hargaCustomCake = 260000; // Jika ukuran 22 cm, harga 260k
    } else if (ukuran.includes('24')) {
        hargaCustomCake = 300000; // Jika ukuran 24 cm, harga 300k
    } else if (ukuran.includes('30')) {
        hargaCustomCake = 450000; // Jika ukuran 30 cm, harga 450k
    }

    // Gabungkan detail custom menjadi satu kalimat untuk disimpan di database (opsional)
    let detailKue = `Ukuran: ${ukuran} | Bentuk: ${bentuk} | Rasa: ${rasa} | Isian: ${isian} | Tema: ${tema} | Tulisan: "${tulisan}"`;
    let csrfToken = document.querySelector('meta[name="csrf-token"]');

    fetch("/checkout/custom/process", {
        method: "POST",
        headers: { "Content-Type": "application/json", "Accept": "application/json", "X-CSRF-TOKEN": csrfToken.getAttribute('content') },
        body: JSON.stringify({
            customer_name: nama,
            customer_email: email,
            customer_phone: nohp,
            ukuran: ukuran,        // Dikirim ke server untuk kalkulasi harga server-side
            total_price: hargaCustomCake,
            delivery_address: alamat,
            custom_details: detailKue,
            delivery_date: tanggal, // Dikirim ke kolom khusus
            notes: catatan // Dikirim ke kolom khusus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.snap_token) {
            // Panggil Pop-up Midtrans
            hideLoader();
            window.snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    window.location.href = "/checkout/invoice/" + data.invoice + "?token=" + data.token;
                },
                onPending: function(result) {
                    window.location.href = "/checkout/invoice/" + data.invoice + "?token=" + data.token; 
                },
                onError: function(result) {
                    Swal.fire({ icon: 'warning', text: 'Pembayaran gagal diproses!' });
                },
                onClose: function() {
                    Swal.fire({ icon: 'warning', text: 'Kamu menutup halaman pembayaran sebelum menyelesaikan transaksi.' }); 
                }
            });
        } else {
            alert("Gagal mendapatkan token: " + (data.error || "Unknown Error"));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        hideLoader();
        Swal.fire({ icon: 'warning', text: 'Terjadi kesalahan sistem saat memproses custom order.' });
    });
        }
    });
}

// Fungsi memasukkan roti ke struk
async function addToPosCart(id, nama, harga, stokAwal) {
    try {
        const response = await fetch('/api/check-stock/' + id);
        const data = await response.json();
        
        if (!response.ok) {
            Swal.fire({ icon: 'error', title: 'Error!', text: data.error || 'Terjadi kesalahan saat memeriksa stok.' });
            return;
        }

        let stokAktual = data.stok;
        
        let stokDisplay = document.getElementById('stok-display-' + id);
        if (stokDisplay) {
            stokDisplay.innerText = 'Sisa: ' + stokAktual + ' pcs';
        }

        let item = posCart.find(i => i.id == id);
        if (item) {
            if (item.qty + 1 > stokAktual) {
                Swal.fire({ icon: 'warning', title: 'Stok Habis!', text: `Tidak bisa menambah pesanan, sisa stok aktual: ${stokAktual} pcs.` });
                return;
            }
            item.qty += 1;
            item.stok = stokAktual; 
        } else {
            if (1 > stokAktual) {
                Swal.fire({ icon: 'warning', title: 'Stok Kosong!', text: 'Produk ini baru saja habis dibeli orang lain.' });
                return;
            }
            posCart.push({ id: id, nama: nama, harga: Number(harga), qty: 1, stok: stokAktual }); 
        }
        renderPosCart();
    } catch (error) {
        console.error(error);
        Swal.fire({ icon: 'error', title: 'Koneksi Gagal', text: 'Gagal menghubungi server untuk cek stok.' });
    }
}

// Fungsi tambah/kurang jumlah roti di struk
async function changePosQty(id, amount) {
    let item = posCart.find(i => i.id == id);
    if (!item) return;

    if (amount > 0) {
        try {
            const response = await fetch('/api/check-stock/' + id);
            const data = await response.json();
            
            if (response.ok) {
                item.stok = data.stok;
                let stokDisplay = document.getElementById('stok-display-' + id);
                if (stokDisplay) {
                    stokDisplay.innerText = 'Sisa: ' + item.stok + ' pcs';
                }
                
                if (item.qty + amount > item.stok) {
                    Swal.fire({ icon: 'warning', title: 'Batas Maksimal!', text: `Sisa stok aktual hanya ${item.stok} pcs.` });
                    return;
                }
            }
        } catch (error) {
            console.error(error);
            Swal.fire({ icon: 'error', title: 'Koneksi Gagal', text: 'Gagal menghubungi server untuk cek stok.' });
            return;
        }
    }

    item.qty += amount;
    // Hapus item dari struk jika qty mencapai 0
    if (item.qty <= 0) {
        posCart = posCart.filter(i => i.id != id);
    }
    
    renderPosCart();
}

// Fungsi mencetak ulang tampilan struk dan menghitung total
function renderPosCart() {
    const container = document.getElementById('pos-cart-items');
    const totalEl = document.getElementById('pos-grand-total');
    
    // Cegah error jika fungsi ini berjalan di halaman pembeli biasa
    if (!container || !totalEl) return;

    let grandTotal = 0;
    container.innerHTML = ''; // Kosongkan tampilan lama

    if (posCart.length === 0) {
        container.innerHTML = '<p style="text-align:center; color:#888; margin-top:50px;"><i class="fa-solid fa-basket-shopping" style="font-size:2rem; margin-bottom:10px;"></i><br>Belum ada pesanan.</p>';
        totalEl.textContent = 'Rp 0';
        const fabCount = document.getElementById('fab-cart-count');
        if(fabCount) fabCount.textContent = '0';
        return;
    }

    posCart.forEach(item => {
        let subtotal = item.harga * item.qty;
        grandTotal += subtotal;

        container.innerHTML += `
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-dashed border-border-light">
                <div>
                    <h4 class="text-[0.95rem] text-text-dark m-0 mb-1">${item.nama}</h4>
                    <div class="text-[0.85rem] text-text-light">Rp ${item.harga.toLocaleString('id-ID')}</div>
                    <div class="text-[0.75rem] text-text-light">Stok: ${item.stok} pcs</div>
                </div>
                <div class="flex items-center gap-2">
                    <button class="bg-bg-light border-none w-6 h-6 rounded-full cursor-pointer font-bold hover:bg-border-medium transition" onclick="changePosQty(${item.id}, -1)">-</button>
                    <input type="number" min="1" max="${item.stok}" value="${item.qty}" oninput="if(this.value !== ''){ if(parseInt(this.value) > parseInt(this.max)) this.value = this.max; if(parseInt(this.value) < 1) this.value = 1; }" onchange="setPosQty(${item.id}, this.value)" class="w-12 h-7 text-center border border-border-dark rounded text-sm font-bold outline-none focus:border-primary-brown">
                    <button class="bg-bg-light border-none w-6 h-6 rounded-full cursor-pointer font-bold hover:bg-border-medium transition" onclick="changePosQty(${item.id}, 1)">+</button>
                </div>
                <div class="font-bold">Rp ${subtotal.toLocaleString('id-ID')}</div>
            </div>
        `;
    });

    totalEl.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
    posGrandTotal = grandTotal; 
    
    // Update FAB cart count
    const fabCount = document.getElementById('fab-cart-count');
    if(fabCount) fabCount.textContent = posCart.reduce((total, item) => total + item.qty, 0);
}

// Fungsi input ketik langsung untuk qty di kasir POS
function setPosQty(id, value) {
    let item = posCart.find(i => i.id == id);
    if (!item) return;
    const newQty = parseInt(value);
    if (isNaN(newQty) || newQty < 1) {
        item.qty = 1;
    } else if (newQty > item.stok) {
        Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Melebihi Stok!', text: `Stok tersedia hanya ${item.stok} pcs.`, showConfirmButton: false, timer: 2000 });
        item.qty = item.stok;
    } else {
        item.qty = newQty;
    }
    renderPosCart();
}

let posGrandTotal = 0;

function openModal() {
    if (posCart.length === 0) return Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Keranjang belanja kosong!' });
    
    // Hitung total murni angka
    posGrandTotal = posCart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
    document.getElementById('modal-total-text').textContent = 'Rp ' + posGrandTotal.toLocaleString('id-ID');
    
    // Reset form modal
    document.getElementById('modal-method').disabled = false;
    document.getElementById('modal-method').value = 'Cash';
    document.getElementById('modal-paid').value = '';
    document.getElementById('modal-change').value = 'Rp 0';
    toggleCashInput();

    document.getElementById('payment-modal').classList.add('flex');
    document.getElementById('payment-modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('payment-modal').classList.remove('flex');
    document.getElementById('payment-modal').classList.add('hidden');
}

function toggleCashInput() {
    const method = document.getElementById('modal-method').value;
    const cashGroup = document.getElementById('cash-input-group');
    const nonCashInfo = document.getElementById('non-cash-info');
    const btnSubmit = document.getElementById('btn-submit-payment');

    // Kosongkan layar embed tiap ganti metode
    nonCashInfo.innerHTML = ''; 

    if (method === 'Cash') {
        cashGroup.style.display = 'block';
        nonCashInfo.style.display = 'none'; 
        if (btnSubmit) {
            btnSubmit.style.display = 'block';
            btnSubmit.innerHTML = 'Bayar & Simpan';
        }
    } else {
        cashGroup.style.display = 'none';
        document.getElementById('modal-change').value = 'Rp 0';
        document.getElementById('modal-paid').value = ''; 
        
        nonCashInfo.style.display = 'block'; 
        nonCashInfo.innerHTML = '<p class="text-sm mb-0">Klik tombol di bawah untuk memunculkan QRIS/Transfer Bank.</p><div id="snap-container" class="mt-3"></div>';
        
        if (btnSubmit) {
            btnSubmit.style.display = 'block';
            btnSubmit.innerHTML = '<i class="fa-solid fa-qrcode"></i> Buat Kode Bayar Midtrans';
        }
    }
}


function calculateChange() {
    const paid = parseInt(document.getElementById('modal-paid').value) || 0;
    const change = paid - posGrandTotal;
    
    if (change >= 0) {
        document.getElementById('modal-change').value = 'Rp ' + change.toLocaleString('id-ID');
        document.getElementById('modal-change').style.color = '#388e3c'; 
    } else {
        document.getElementById('modal-change').value = 'Uang Kurang!';
        document.getElementById('modal-change').style.color = '#d32f2f'; 
    }
}

function submitFinalPayment() {
    const method = document.getElementById('modal-method').value;
    let paid = parseInt(document.getElementById('modal-paid').value) || 0;
    
    if (method === 'Cash' && paid < posGrandTotal) {
        return Swal.fire({ icon: 'warning', text: 'Nominal uang yang dibayarkan kurang dari total tagihan!' });
    }

    let finalPaid = method === 'Cash' ? paid : posGrandTotal;
    let change = method === 'Cash' ? paid - posGrandTotal : 0;

    document.getElementById('cart-data-input').value = JSON.stringify(posCart);
    document.getElementById('input-method').value = method;
    document.getElementById('input-paid').value = finalPaid;
    document.getElementById('input-change').value = change;

    if (method === 'Cash') {
        showLoader();
        document.getElementById('form-pos').submit();
    } else {
        // Mode Transfer Midtrans
        const btnSubmit = document.getElementById('btn-submit-payment');
        const snapContainer = document.getElementById('snap-container');
        
        btnSubmit.style.display = 'none';
        document.getElementById('modal-method').disabled = true; // dropdown mati
        
        snapContainer.innerHTML = '<div class="text-center py-5"><i class="fa-solid fa-circle-notch fa-spin text-3xl text-primary-brown mb-3"></i><br><strong>Menghubungkan ke server Midtrans...</strong></div>';

        let csrfToken = document.querySelector('meta[name="csrf-token"]');
        
        fetch("/kasir/proses", {
            method: "POST",
            headers: { 
                "Content-Type": "application/json", 
                "Accept": "application/json", 
                "X-CSRF-TOKEN": csrfToken.getAttribute('content') 
            },
            body: JSON.stringify({
                cart_data: JSON.stringify(posCart),
                payment_method: method,
                amount_paid: finalPaid,
                change_amount: change
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.snap_token) {
                snapContainer.innerHTML = '';
                snapContainer.style = 'width: 100%; border-radius: 10px; overflow: hidden; border: 1px solid #e0e0e0; min-height: 350px;';
                
                window.snap.embed(data.snap_token, {
                    embedId: 'snap-container',
                    onSuccess: function(result) {
                        window.location.href = "/kasir/selesai/" + data.transaction_id;
                    },
                    onPending: function(result) {
                        window.location.href = "/kasir/selesai/" + data.transaction_id;
                    },
                    onError: function(result) {
                        Swal.fire({ icon: 'error', text: 'Pembayaran gagal!' });
                        btnSubmit.style.display = 'block';
                        document.getElementById('modal-method').disabled = false;
                    }
                });
            } else {
                snapContainer.innerHTML = '<p class="text-danger font-bold text-center">Gagal memuat token Midtrans.</p>';
                btnSubmit.style.display = 'block';
                document.getElementById('modal-method').disabled = false;
            }
        })
        .catch(error => {
            console.error(error);
            snapContainer.innerHTML = '<p class="text-danger font-bold text-center">Terjadi kesalahan koneksi server. Stok mungkin habis.</p>';
            btnSubmit.style.display = 'block';
            document.getElementById('modal-method').disabled = false;
        });
    }
}

// ==========================================
// 7. FUNGSI PEMBAYARAN ONLINE (MIDTRANS)
// ==========================================
function payNow() {
    // 1. Cek apakah keranjang kosong menggunakan variabel global 'cart'
    if (cart.length === 0) {
        return Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Keranjang belanja kosong!' });
    }

    // 2. Hitung total harga (perhatikan nama propertinya: price & quantity)
    let grandTotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    // 3. Ambil data formulir pembeli
    let namaPembeli = document.getElementById('nama')?.value;
    let emailPembeli = document.getElementById('email')?.value; // Tambahan email
    let noHp = document.getElementById('nohp')?.value;
    let alamat = document.getElementById('alamat')?.value;

// (Lanjutan dari fungsi payNow, di bawah pengambilan data)

    // PALANG PINTU VALIDASI CHECKOUT BIASA
    if (!namaPembeli || namaPembeli.trim() === "") {
        Swal.fire({ icon: 'warning', text: 'Mohon isi Nama Anda!' });
        document.getElementById('nama').focus();
        return; // Menghentikan script
    }

    const regexHuruf = /^[A-Za-z\s]+$/;
    if (!regexHuruf.test(namaPembeli)) {
        Swal.fire({ icon: 'warning', text: 'Nama hanya boleh berisi huruf dan spasi (tanpa angka/simbol)!' });
        document.getElementById('nama').focus();
        return;
    }

    if (!emailPembeli || emailPembeli.trim() === "") {
        Swal.fire({ icon: 'warning', text: 'Mohon isi Email Anda!' });
        document.getElementById('email').focus();
        return;
    }

    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(emailPembeli)) {
        Swal.fire({ icon: 'warning', text: 'Format Email tidak valid! (Contoh: nama@email.com)' });
        document.getElementById('email').focus();
        return;
    }

    if (!noHp || noHp.trim() === "") {
        Swal.fire({ icon: 'warning', text: 'Mohon isi No HP Anda!' });
        document.getElementById('nohp').focus();
        return;
    }

    // Validasi No HP: hanya angka, panjang 10-13 digit
    const regexPhone = /^[0-9]{10,13}$/;
    if (!regexPhone.test(noHp.trim())) {
        Swal.fire({ icon: 'warning', text: 'No HP hanya boleh berisi angka, minimal 10 dan maksimal 13 digit!' });
        document.getElementById('nohp').focus();
        return;
    }

    // CATATAN: Kalau di form checkout biasa ini kamu JUGA punya input tanggal pengiriman (misal id-nya 'tanggal_kirim'), tambahkan juga seperti ini:
    let tanggalKirim = document.getElementById('tanggal_kirim')?.value;
    if (!tanggalKirim || tanggalKirim.trim() === "") {
         Swal.fire({ icon: 'warning', text: 'Mohon isi Tanggal Pengiriman!' });
         document.getElementById('tanggal_kirim').focus();
         return;
    }

    // === SATPAM ANTI TANGGAL MASA LALU (MANUAL KETIK) ===
    const hariIniCheckout = new Date().toISOString().split('T')[0];
    if (tanggalKirim < hariIniCheckout) {
        Swal.fire({ icon: 'warning', text: 'Tanggal pengiriman tidak boleh berlalu (kurang dari hari ini)!' });
        document.getElementById('tanggal_kirim').value = ""; // Kosongkan
        document.getElementById('tanggal_kirim').focus();
        return; 
    }

    if (!alamat || alamat.trim() === "") {
        Swal.fire({ icon: 'warning', text: 'Mohon isi Alamat Pengiriman!' });
        document.getElementById('alamat').focus();
        return;
    }

    // Validasi Alamat: maksimal 300 karakter
    if (alamat.trim().length > 300) {
        Swal.fire({ icon: 'warning', text: 'Detail Alamat terlalu panjang! Maksimal 300 karakter.' });
        document.getElementById('alamat').focus();
        return;
    }

    if (noHp.trim().length > 15) {
        Swal.fire({ icon: 'warning', text: 'Detail Alamat terlalu panjang! Maksimal 300 karakter.' });
        document.getElementById('nohp').focus();
        return;
    }

    // Validasi Catatan: maksimal 200 karakter (ambil nilai sekarang)
    let catatanVal = document.getElementById('catatan')?.value || '';
    if (catatanVal.trim().length > 200) {
        Swal.fire({ icon: 'warning', text: 'Catatan terlalu panjang! Maksimal 200 karakter.' });
        document.getElementById('catatan').focus();
        return;
    }

    // === TAMBAHAN GERBANG KONFIRMASI ===
    Swal.fire({
        title: 'Konfirmasi Checkout?',
        text: "Sebelum lakukan checkout pastikan pesanan kamu sudah sesuai ya",
        iconHtml: '<i class="fa-solid fa-cart-shopping" style="color: var(--color-dark-brown);"></i>',
        showCancelButton: true,
        confirmButtonColor: '#a67c52',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya. saya yakin',
        cancelButtonText: 'Kembali'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoader();

    // 4. Ambil CSRF Token dari tag <meta> di layout utama
    let csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        return Swal.fire({ icon: 'warning', text: 'Error: Meta CSRF Token tidak ditemukan di layout!' });
    }

    let catatan = document.getElementById('catatan')?.value || "-";

    fetch("/checkout/process", {
        method: "POST",
        headers: { "Content-Type": "application/json", "Accept": "application/json", "X-CSRF-TOKEN": csrfToken.getAttribute('content') },
        body: JSON.stringify({
            total_price: grandTotal, 
            items: cart,
            customer_name: namaPembeli,
            customer_email: emailPembeli,
            customer_phone: noHp,
            delivery_address: alamat,
            delivery_date: tanggalKirim,
            notes: catatan 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.snap_token) {
            // Munculkan Pop-up Midtrans DULU (Jangan hapus keranjang di sini)
            hideLoader();
            window.snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    // Hapus keranjang belanja karena sudah lunas
                    localStorage.removeItem('alfazza_cart'); 
                    cart = []; 
                    updateCartUI(); 

                    // ALAHKAN KE HALAMAN SUKSES CODASHOP (Membawa data invoice dari backend)
                    window.location.href = "/checkout/invoice/" + data.invoice + "?token=" + data.token;
                },
                onPending: function(result) {
                    localStorage.removeItem('alfazza_cart'); 
                    cart = []; 
                    updateCartUI();

                    // Alihkan juga ke halaman sukses tapi statusnya nanti pending
                    window.location.href = "/checkout/invoice/" + data.invoice + "?token=" + data.token; 
                },
                onError: function(result) {
                    Swal.fire({ icon: 'warning', text: 'Pembayaran gagal diproses!' });
                },
                onClose: function() {
                    Swal.fire({ icon: 'warning', text: 'Kamu menutup halaman pembayaran sebelum menyelesaikan transaksi.' }); 
                }
            });
        } else {
            alert("Gagal mendapatkan token: " + (data.error || "Unknown Error"));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        hideLoader();
        Swal.fire({ icon: 'warning', text: 'Terjadi kesalahan sistem saat memproses pembayaran.' });
    });
        }
    });
}


// ==========================================
// 8. GLOBAL LOADING SCREEN
// ==========================================
document.addEventListener("DOMContentLoaded", () => {
    if (!document.getElementById('global-loader')) {
        const loaderHTML = `
            <div id="global-loader" class="fixed inset-0 bg-black/60 z-[9999] flex-col justify-center items-center hidden">
                <i class="fa-solid fa-circle-notch animate-spin text-5xl text-primary-brown mb-4"></i>
                <p class="text-white font-bold text-lg m-0 mt-4">Memproses data...</p>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', loaderHTML);
    }
});

window.showLoader = function() {
    const loader = document.getElementById('global-loader');
    if(loader) {
        loader.classList.remove('hidden');
        loader.classList.add('flex');
    }
}

window.hideLoader = function() {
    const loader = document.getElementById('global-loader');
    if(loader) {
        loader.classList.remove('flex');
        loader.classList.add('hidden');
    }

}

// === FUNGSI PENGHITUNG KARAKTER OTOMATIS ===
function updateCounter(inputId, counterId, max) {
    // 1. Ambil elemen input dan elemen teks angkanya
    const inputElement = document.getElementById(inputId);
    const counterElement = document.getElementById(counterId);
    
    // 2. Hitung jumlah huruf yang sedang diketik
    const currentLength = inputElement.value.length;
    
    // 3. Ubah teksnya menjadi Format: AngkaSekarang/Maksimal (Contoh: 12/100)
    counterElement.innerText = `${currentLength}/${max}`;

    // Opsional (Biar Keren): Ubah warnanya jadi merah kalau sudah penuh/maksimal
    if (currentLength >= max) {
        counterElement.classList.add('text-red-500');
        counterElement.classList.remove('text-gray-500');
    } else {
        counterElement.classList.remove('text-red-500');
        counterElement.classList.add('text-gray-500');
    }
}
