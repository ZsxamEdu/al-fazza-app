// 2. State & inisialisasi
let cart = JSON.parse(localStorage.getItem('alfazza_cart')) || [];
let posCart = [];

document.addEventListener("DOMContentLoaded", () => {
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
        if(cart.length === 0) return alert("Keranjang kosong!");
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
    }

    hamburger?.addEventListener('click', () => {
        mainNav?.classList.contains('open') ? closeNav() : openNav();
    });

    navOverlay?.addEventListener('click', closeNav);

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

function addToCart(nama, harga, gambar, qty = 1) {
    let item = cart.find(i => i.name === nama);
    if (item) item.quantity += qty;
    else cart.push({ name: nama, price: harga, quantity: qty, image: gambar });
    
    saveCart();
    alert(`${qty} ${nama} berhasil ditambahkan!`);
}

function removeFromCart(index) {
    cart.splice(index, 1);
    saveCart();
}

function updateCartUI() {
    const elCount = document.getElementById('cart-count');
    const elItems = document.querySelector('.cart-items');
    const elTotal = document.querySelector('.cart-total span:nth-child(2)');
    
    if (elCount) elCount.textContent = cart.reduce((tot, item) => tot + item.quantity, 0);

    if (elItems) {
        let grandTotal = 0;
        elItems.innerHTML = cart.map((item, index) => {
            let sub = item.price * item.quantity;
            grandTotal += sub;
            return `
                <div class="cart-item">
                    <img src="${item.image}" alt="${item.name}">
                    <div class="item-detail">
                        <h4>${item.name}</h4><p>Qty: ${item.quantity}</p>
                        <span>Rp ${item.price.toLocaleString('id-ID')}</span>
                    </div>
                    <button class="remove-item" onclick="removeFromCart(${index})"><i class="fas fa-trash"></i></button>
                </div>`;
        }).join('');
        if (elTotal) elTotal.textContent = `Rp ${grandTotal.toLocaleString('id-ID')}`;
    }
}

// Fungsi tombol +/- di halaman detail
function changeQty(amount) {
    const qtyInput = document.getElementById('qty');
    if(qtyInput && parseInt(qtyInput.value) + amount >= 1) {
        qtyInput.value = parseInt(qtyInput.value) + amount;
    }
}

// 5. Checkout
function renderCheckoutSummary() {
    const list = document.getElementById('checkout-order-list');
    if (!list) return;

    if (cart.length === 0) {
        alert("Keranjang kosong!");
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

function prosesCheckoutWA() {
    const form = ['nama', 'nohp', 'alamat'].map(id => document.getElementById(id).value);
    if (form.includes('')) return alert("Lengkapi Nama, No HP, dan Alamat!");

    let wa = `Halo *AL-Fazza Bakery*, saya pesan:\n\n*PEMESAN*\nNama: ${form[0]}\nHP: ${form[1]}\nAlamat: ${form[2]}\n\n*PESANAN*\n`;
    let total = 0;
    
    cart.forEach(i => {
        let sub = i.price * i.quantity; total += sub;
        wa += `- ${i.quantity}x ${i.name} (Rp ${sub.toLocaleString('id-ID')})\n`;
    });
    
    window.open(`https://wa.me/6281221315946?t  ext=${encodeURIComponent(wa + `\n*Total: Rp ${total.toLocaleString('id-ID')}*`)}`);
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

// Fungsi Checkout Custom Order via WA
function prosesCustomOrderWA() {
    // Ambil data pemesan
    const nama = document.getElementById('co_nama').value;
    const nohp = document.getElementById('co_nohp').value;
    
    // Ambil spesifikasi
    const ukuran = document.getElementById('co_ukuran').value;
    const bentuk = document.getElementById('co_bentuk').value;
    const rasa = document.getElementById('co_rasa').value;
    const isian = document.getElementById('co_isian').value;
    
    // Ambil desain
    const tema = document.getElementById('co_tema').value;
    const tulisan = document.getElementById('co_tulisan').value || "-";
    
    // Ambil pengiriman & catatan
    const tanggal = document.getElementById('co_tanggal').value;
    const metode = document.getElementById('co_metode').value;
    const alamat = document.getElementById('co_alamat').value || "-";
    const catatan = document.getElementById('co_catatan').value || "-";

    // Validasi input wajib
    if (!nama || !nohp || !tema || !tanggal) {
        return alert("Mohon lengkapi Nama, No HP, Tema/Warna, dan Tanggal Diperlukan!");
    }
    if (metode === "Dikirim" && !alamat) {
        return alert("Mohon isi alamat pengiriman!");
    }

    // Format Pesan WA
    let wa = `Halo *AL-Fazza Bakery*, saya ingin melakukan *Custom Order Cake*.\n\n`;
    wa += `*DATA PEMESAN*\n- Nama: ${nama}\n- HP: ${nohp}\n\n`;
    wa += `*SPESIFIKASI KUE*\n- Ukuran: ${ukuran}\n- Bentuk: ${bentuk}\n- Base Cake: ${rasa}\n- Isian: ${isian}\n\n`;
    wa += `*DETAIL DESAIN*\n- Tema/Warna: ${tema}\n- Tulisan di Kue: ${tulisan}\n\n`;
    wa += `*PENGIRIMAN*\n- Tanggal: ${tanggal}\n- Metode: ${metode}\n`;
    
    if (metode === "Dikirim") {
        wa += `- Alamat: ${alamat}\n`;
    }
    
    wa += `\n📝 *Catatan Tambahan:* ${catatan}\n\n`;
    wa += `_(Saya akan mengirimkan gambar referensi desainnya setelah pesan ini)_`;

    window.open(`https://wa.me/6281221315946?text=${encodeURIComponent(wa)}`);
}

// Fungsi memasukkan roti ke struk
function addToPosCart(id, nama, harga) {
    let item = posCart.find(i => i.id == id);
    if (item) {
        item.qty += 1; // Jika sudah ada, tambah jumlahnya
    } else {
        posCart.push({ id: id, nama: nama, harga: harga, qty: 1 }); // Jika belum ada, masukkan baru
    }
    renderPosCart();
}

// Fungsi tambah/kurang jumlah roti di struk
function changePosQty(id, amount) {
    let item = posCart.find(i => i.id == id);
    if (item) {
        item.qty += amount;
        // Hapus item dari struk jika qty mencapai 0
        if (item.qty <= 0) {
            posCart = posCart.filter(i => i.id != id);
        }
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
        return;
    }

    posCart.forEach(item => {
        let subtotal = item.harga * item.qty;
        grandTotal += subtotal;

        container.innerHTML += `
            <div class="cart-item">
                <div class="cart-item-info">
                    <h4>${item.nama}</h4>
                    <div class="cart-item-price">Rp ${item.harga.toLocaleString('id-ID')}</div>
                </div>
                <div class="cart-item-qty">
                    <button class="btn-qty" onclick="changePosQty(${item.id}, -1)">-</button>
                    <span>${item.qty}</span>
                    <button class="btn-qty" onclick="changePosQty(${item.id}, 1)">+</button>
                </div>
                <div style="font-weight: bold;">Rp ${subtotal.toLocaleString('id-ID')}</div>
            </div>
        `;
    });

    totalEl.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
}

let posGrandTotal = 0;

function openModal() {
    if (posCart.length === 0) return alert("Keranjang kosong!");
    
    // Hitung total murni angka
    posGrandTotal = posCart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
    document.getElementById('modal-total-text').textContent = 'Rp ' + posGrandTotal.toLocaleString('id-ID');
    
    // Reset form modal
    document.getElementById('modal-paid').value = '';
    document.getElementById('modal-change').value = 'Rp 0';
    toggleCashInput();

    document.getElementById('payment-modal').classList.add('active');
}

function closeModal() {
    document.getElementById('payment-modal').classList.remove('active');
}

function toggleCashInput() {
    const method = document.getElementById('modal-method').value;
    const cashGroup = document.getElementById('cash-input-group');
    
    // Elemen tambahan untuk Non-Tunai
    const nonCashInfo = document.getElementById('non-cash-info');
    const qrisImg = document.getElementById('qris-image');
    const transferInfo = document.getElementById('transfer-info');
    const instruction = document.getElementById('payment-instruction');

    if (method === 'Cash') {
        cashGroup.style.display = 'block';
        nonCashInfo.style.display = 'none'; // Sembunyikan info qris/transfer
    } else {
        cashGroup.style.display = 'none';
        document.getElementById('modal-change').value = 'Rp 0';
        document.getElementById('modal-paid').value = ''; 
        
        nonCashInfo.style.display = 'block'; // Tampilkan kotak peringatan mutasi

        if (method === 'QRIS') {
            instruction.textContent = "Silakan scan QRIS berikut:";
            qrisImg.style.display = 'block';
            transferInfo.style.display = 'none';
        } else if (method === 'Transfer') {
            instruction.textContent = "Silakan transfer ke rekening berikut:";
            qrisImg.style.display = 'none';
            transferInfo.style.display = 'block';
        }
    }
}


function calculateChange() {
    const paid = parseInt(document.getElementById('modal-paid').value) || 0;
    const change = paid - posGrandTotal;
    
    if (change >= 0) {
        document.getElementById('modal-change').value = 'Rp ' + change.toLocaleString('id-ID');
        document.getElementById('modal-change').style.color = '#388e3c'; // Hijau kalau cukup
    } else {
        document.getElementById('modal-change').value = 'Uang Kurang!';
        document.getElementById('modal-change').style.color = '#d32f2f'; // Merah kalau kurang
    }
}

function submitFinalPayment() {
    const method = document.getElementById('modal-method').value;
    let paid = parseInt(document.getElementById('modal-paid').value) || 0;
    
    // Kalau bayar cash, uang tidak boleh kurang
    if (method === 'Cash' && paid < posGrandTotal) {
        return alert("Nominal uang yang dibayarkan kurang dari total tagihan!");
    }

    // Kalau bukan cash, anggap uang pas
    if (method !== 'Cash') {
        paid = posGrandTotal; 
    }

    let change = paid - posGrandTotal;

    // Masukkan data ke form tersembunyi
    document.getElementById('cart-data-input').value = JSON.stringify(posCart);
    document.getElementById('input-method').value = method;
    document.getElementById('input-paid').value = paid;
    document.getElementById('input-change').value = change;

    // Submit form
    document.getElementById('form-pos').submit();
}

// ==========================================
// 7. FUNGSI PEMBAYARAN ONLINE (MIDTRANS)
// ==========================================
function payNow() {
    // 1. Cek apakah keranjang kosong menggunakan variabel global 'cart'
    if (cart.length === 0) {
        return alert('Keranjang belanja kosong!');
    }

    // 2. Hitung total harga (perhatikan nama propertinya: price & quantity)
    let grandTotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    // 3. Ambil data formulir pembeli
    let namaPembeli = document.getElementById('nama')?.value;
    let emailPembeli = document.getElementById('email')?.value; // Tambahan email
    let noHp = document.getElementById('nohp')?.value;
    let alamat = document.getElementById('alamat')?.value;

    if (!namaPembeli || !emailPembeli || !noHp || !alamat) {
        return alert('Mohon lengkapi Nama, Email, No HP, dan Alamat Pengiriman!');
    }

    // 4. Ambil CSRF Token dari tag <meta> di layout utama
    let csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        return alert("Error: Meta CSRF Token tidak ditemukan di layout!");
    }

    // 5. Kirim data ke Laravel Controller
    fetch("/checkout/process", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken.getAttribute('content')
        },
        body: JSON.stringify({
            total_price: grandTotal, 
            items: cart,
            customer_name: namaPembeli,
            customer_email: emailPembeli, // Data email dikirim ke Laravel
            customer_phone: noHp,
            delivery_address: alamat
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.snap_token) {
            // Munculkan Pop-up Midtrans DULU (Jangan hapus keranjang di sini)
            window.snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    // Hapus keranjang belanja karena sudah lunas
                    localStorage.removeItem('alfazza_cart'); 
                    cart = []; 
                    updateCartUI(); 

                    // ALAHKAN KE HALAMAN SUKSES CODASHOP (Membawa data invoice dari backend)
                    window.location.href = "/checkout/invoice/" + data.invoice;
                },
                onPending: function(result) {
                    localStorage.removeItem('alfazza_cart'); 
                    cart = []; 
                    updateCartUI();

                    // Alihkan juga ke halaman sukses tapi statusnya nanti pending
                    window.location.href = "/checkout/invoice/" + data.invoice; 
                },
                onError: function(result) {
                    alert("Pembayaran gagal diproses!");
                },
                onClose: function() {
                    alert('Kamu menutup halaman pembayaran sebelum menyelesaikan transaksi.'); 
                }
            });
        } else {
            alert("Gagal mendapatkan token: " + (data.error || "Unknown Error"));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Terjadi kesalahan sistem saat memproses pembayaran.");
    });
}
