@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
    <div class="header-action">
        <div>
            <h1>Manajemen Pesanan</h1>
            <p>Kelola dan periksa detail pesanan pelanggan.</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-container" style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <form action="{{ route('admin.pesanan.index') }}" method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 150px;">
                <label>Filter Status:</label>
                <select name="status" class="form-control" style="width: 100%; padding: 8px;">
                    <option value="">Semua Status</option>
                    <option value="baru" {{ request('status') == 'baru' ? 'selected' : '' }}>Pesanan Baru</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Sedang Dibuat</option>
                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div style="flex: 1; min-width: 150px;">
                <label>Jenis Pesanan:</label>
                <select name="jenis" class="form-control" style="width: 100%; padding: 8px;">
                    <option value="">Semua Jenis</option>
                    <option value="online" {{ request('jenis') == 'online' ? 'selected' : '' }}>Online (Roti Biasa)</option>
                    <option value="custom-order" {{ request('jenis') == 'custom-order' ? 'selected' : '' }}>Custom Cake</option>
                    <option value="kasir" {{ request('jenis') == 'kasir' ? 'selected' : '' }}>Kasir (Offline)</option>
                </select>
            </div>

            <div>
                <button type="submit" class="btn-tambah" style="padding: 10px 20px;"><i class="fa-solid fa-filter"></i> Filter</button>
                <a href="{{ route('admin.pesanan.index') }}" class="btn-tambah" style="background: #e2e8f0; color: #333; text-decoration: none; padding: 10px 20px;">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Invoice & Waktu</th>
                    <th>Info Pelanggan & Pengiriman</th>
                    <th>Detail Item / Custom Cake</th>
                    <th>Status</th>
                    <th>Ubah Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanan as $p)
                <tr>
                    <!-- Kolom 1: Invoice -->
                                        <td style="vertical-align: top;">
                        <strong>{{ $p->invoice_number }}</strong><br>
                        <small>Dipesan: {{ $p->created_at->format('d M Y') }}</small><br>
                        
                        <!-- Tampilkan Tanggal Harus Dikirim -->
                        @if($p->delivery_date)
                            <div style="margin-top: 5px; padding: 4px; background: #fff3e0; border: 1px solid #ffe0b2; border-radius: 4px; font-size: 11px; color: #e65100; display: inline-block;">
                                <i class="fa-regular fa-calendar-check"></i> Krm: <strong>{{ \Carbon\Carbon::parse($p->delivery_date)->format('d M Y') }}</strong>
                            </div><br>
                        @endif

                        <span style="display: inline-block; margin-top: 5px; padding: 3px 6px; border-radius: 4px; font-size: 11px; font-weight: bold; 
                            {{ $p->order_type == 'custom-order' ? 'background: #9b59b6; color: white;' : ($p->order_type == 'kasir' ? 'background: #a67c52; color: white;' : 'background: #3498db; color: white;') }}">
                            {{ strtoupper($p->order_type) }}
                        </span>
                    </td>
                    
                    <!-- Kolom 2: Info Pengiriman -->
                    <td style="vertical-align: top; max-width: 250px;">
                        <strong>{{ $p->customer_name }}</strong><br>
                        <i class="fa-solid fa-phone"></i> {{ $p->customer_phone ?? '-' }}<br>
                        <hr style="margin: 5px 0; border: 0.5px solid #eee;">
                        <span style="font-size: 12px; color: #555;">{{ $p->delivery_address ?? 'Beli di Tempat (Kasir)' }}</span>
                        @if($p->notes && $p->notes != '-')
                            <div style="margin-top: 5px; font-size: 11px; color: #d35400; background: #fdf2e9; padding: 4px; border-radius: 4px;">
                                <strong>Catatan:</strong> {{ $p->notes }}
                            </div>
                        @endif
                    </td>
                    
                    <!-- Kolom 3: Detail Produk -->
                    <td style="vertical-align: top; max-width: 250px;">
                        @if($p->order_type == 'custom-order')
                            <!-- Tampilkan Detail Custom Cake -->
                            <div style="font-size: 13px; color: #333; background: #f9f9f9; padding: 8px; border-radius: 6px;">
                                {!! str_replace(', ', '<br>', $p->custom_details) !!}
                            </div>
                        @else
                            <!-- Tampilkan List Roti Biasa -->
                            <ul style="padding-left: 20px; margin: 0; font-size: 13px;">
                                @foreach($p->details as $detail)
                                    <li>{{ $detail->product->nama ?? 'Produk Dihapus' }} (x{{ $detail->qty }})</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>

                    <!-- Kolom 4: Badge Status -->
                    <td style="vertical-align: top;">
                        @if($p->order_status == 'baru')
                            <span style="background: #e74c3c; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Pesanan Baru</span>
                        @elseif($p->order_status == 'diproses')
                            <span style="background: #f39c12; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Diproses/Dibuat</span>
                        @elseif($p->order_status == 'dikirim')
                            <span style="background: #3498db; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Sedang Dikirim</span>
                        @else
                            <span style="background: #2ecc71; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Selesai</span>
                        @endif
                    </td>

                    <!-- Kolom 5: Form Ubah Status -->
                    <td style="vertical-align: top;">
                        <form action="{{ route('admin.pesanan.updateStatus', $p->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="order_status" onchange="this.form.submit()" style="padding: 6px; border-radius: 4px; width: 100%;">
                                <option value="baru" {{ $p->order_status == 'baru' ? 'selected' : '' }}>Baru</option>
                                <option value="diproses" {{ $p->order_status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="dikirim" {{ $p->order_status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="selesai" {{ $p->order_status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px;">Tidak ada pesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection