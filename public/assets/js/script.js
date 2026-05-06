// 2. State & inisialisasi
let cart = JSON.parse(localStorage.getItem('alfazza_cart')) || [];

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
    
    window.open(`https://wa.me/6281221315946?text=${encodeURIComponent(wa + `\n*Total: Rp ${total.toLocaleString('id-ID')}*`)}`);
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