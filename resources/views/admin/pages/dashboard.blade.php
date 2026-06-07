@extends('admin.layouts.app')

@section('content')

    {{-- ============================================================ --}}
    {{-- HEADER HALAMAN                                               --}}
    {{-- ============================================================ --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1 font-medium">
            Ringkasan bisnis Natuna GAS —
            <span class="text-[#06728A] font-semibold">{{ now()->translatedFormat('d F Y') }}</span>
        </p>
    </div>

    {{-- ============================================================ --}}
    {{-- SECTION 1: 4 KARTU METRIK UTAMA                             --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Card 1: Pendapatan Lunas Bulan Ini --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Bulan Ini</span>
            </div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pendapatan Lunas</p>
            <p class="text-xl font-black text-gray-900 leading-tight">
                Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
            </p>
        </div>

        {{-- Card 2: Total Piutang --}}
        <div class="bg-white rounded-2xl p-5 border border-orange-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z">
                        </path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">Perlu Tagih</span>
            </div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Piutang</p>
            <p class="text-xl font-black text-gray-900 leading-tight">
                Rp {{ number_format($totalPiutang, 0, ',', '.') }}
            </p>
        </div>

        {{-- Card 3: Pesanan Menunggu --}}
        <div class="bg-white rounded-2xl p-5 border border-[#CBE4ED] shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 bg-[#F0F8FA] rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#06728A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                @if ($pesananMenunggu > 0)
                    <span class="text-[10px] font-bold text-[#06728A] bg-[#E0F2F7] px-2 py-1 rounded-full animate-pulse">
                        Perlu Aksi
                    </span>
                @endif
            </div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Menunggu Konfirmasi</p>
            <p class="text-xl font-black text-gray-900 leading-tight">
                {{ $pesananMenunggu }}
                <span class="text-sm font-bold text-gray-400">pesanan</span>
            </p>
        </div>

        {{-- Card 4: Total Produk Aktif --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Produk</p>
            <p class="text-xl font-black text-gray-900 leading-tight">
                {{ $totalProdukAktif }}
                <span class="text-sm font-bold text-gray-400">produk</span>
            </p>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SECTION 2: GRAFIK PENJUALAN PER BULAN                       --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">

        {{-- Header Chart + Filter Dropdown --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-base font-bold text-gray-900">Grafik Penjualan</h2>
                <p class="text-xs text-gray-400 font-medium mt-0.5">Tahun {{ now()->year }}</p>
            </div>

            {{-- Filter Buttons (Vanilla JS akan handle ini) --}}
            <div class="flex gap-2 bg-gray-100 p-1 rounded-xl self-start sm:self-auto" id="chart-filter-group">
                <button onclick="filterChart('semua')" data-filter="semua"
                    class="chart-filter-btn px-4 py-1.5 rounded-lg text-xs font-bold transition-all bg-white text-[#06728A] shadow-sm">
                    Semua
                </button>
                <button onclick="filterChart('lunas')" data-filter="lunas"
                    class="chart-filter-btn px-4 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-700">
                    Lunas
                </button>
                <button onclick="filterChart('belum_lunas')" data-filter="belum_lunas"
                    class="chart-filter-btn px-4 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-700">
                    Belum Lunas
                </button>
            </div>
        </div>

        {{-- Canvas untuk Chart.js --}}
        <div class="relative" style="height: 280px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SECTION 3: DUA TABEL BERSISIAN                              --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- TABEL 1: TOP 5 DEBITUR (Hutang Terbesar) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Top Piutang Pelanggan</h2>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">5 pelanggan dengan hutang terbesar</p>
                </div>
                <div class="w-8 h-8 bg-orange-50 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                    </svg>
                </div>
            </div>

            @if ($topDebitur->isEmpty())
                <div class="p-10 text-center">
                    <p class="text-sm text-gray-400 font-medium">Tidak ada piutang aktif. 🎉</p>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach ($topDebitur as $index => $debitur)
                        <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50/50 transition-colors">
                            {{-- Rank number --}}
                            <span
                                class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black shrink-0
                                {{ $index === 0 ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-500' }}">
                                {{ $index + 1 }}
                            </span>

                            {{-- Nama & Toko --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-800 truncate">{{ $debitur->name }}</p>
                                @if ($debitur->shop_name)
                                    <p class="text-[11px] text-gray-400 font-medium truncate">{{ $debitur->shop_name }}</p>
                                @endif
                            </div>

                            {{-- Sisa Hutang --}}
                            <div class="text-right shrink-0">
                                <p class="text-sm font-black text-orange-600">
                                    Rp {{ number_format($debitur->sisa_hutang, 0, ',', '.') }}
                                </p>
                                <p class="text-[10px] text-gray-400 font-medium">sisa hutang</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- TABEL 2: TOP 5 PRODUK TERLARIS --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Produk Terlaris</h2>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">5 produk dengan penjualan tertinggi</p>
                </div>
                <div class="w-8 h-8 bg-violet-50 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>

            @if ($topProduk->isEmpty())
                <div class="p-10 text-center">
                    <p class="text-sm text-gray-400 font-medium">Belum ada data penjualan.</p>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach ($topProduk as $index => $produk)
                        <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50/50 transition-colors">
                            {{-- Rank number --}}
                            <span
                                class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black shrink-0
                                {{ $index === 0 ? 'bg-[#06728A] text-white' : 'bg-gray-100 text-gray-500' }}">
                                {{ $index + 1 }}
                            </span>

                            {{-- Nama Produk --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-800 truncate">{{ $produk->nama_produk }}</p>
                                <p class="text-[11px] text-gray-400 font-medium">
                                    Revenue: Rp {{ number_format($produk->total_revenue, 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- Total Terjual + Progress Bar Visual --}}
                            <div class="text-right shrink-0">
                                <p class="text-sm font-black text-[#06728A]">
                                    {{ number_format($produk->total_terjual, 0, ',', '.') }}
                                </p>
                                <p class="text-[10px] text-gray-400 font-medium">unit terjual</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SCRIPT CHART.JS + VANILLA JS FILTER                         --}}
    {{-- ============================================================ --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // --- DATA DARI CONTROLLER (PHP -> JavaScript) ---
        // json_encode otomatis mengubah array PHP menjadi array JS
        const dataSemuaBulan = @json($chartSemuaData);
        const dataLunasBulan = @json($chartLunasData);
        const dataBelumBulan = @json($chartBelumData);

        // Label 12 bulan dalam Bahasa Indonesia
        const labelBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Ambil context canvas untuk Chart.js
        const ctx = document.getElementById('salesChart').getContext('2d');

        // Buat gradient untuk tampilan chart yang lebih menarik
        const gradientHijau = ctx.createLinearGradient(0, 0, 0, 280);
        gradientHijau.addColorStop(0, 'rgba(6, 114, 138, 0.15)');
        gradientHijau.addColorStop(1, 'rgba(6, 114, 138, 0)');

        // --- INISIALISASI CHART.JS ---
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelBulan,
                datasets: [{
                    label: 'Total Penjualan',
                    data: dataSemuaBulan, // Default tampilkan semua
                    borderColor: '#06728A',
                    backgroundColor: gradientHijau,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#06728A',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4, // Lengkungan garis (0 = lurus, 1 = sangat melengkung)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }, // Sembunyikan legend bawaan Chart.js
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#94a3b8',
                        bodyColor: '#f8fafc',
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            // Format angka di tooltip menjadi format rupiah
                            label: function(context) {
                                const nilai = context.parsed.y;
                                return ' Rp ' + new Intl.NumberFormat('id-ID').format(nilai);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }, // Hilangkan grid vertikal
                        border: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                weight: '600'
                            },
                            color: '#94a3b8'
                        }
                    },
                    y: {
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false
                        },
                        border: {
                            display: false,
                            dash: [4, 4]
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#94a3b8',
                            // Format sumbu Y menjadi singkatan (misal: 1.5 Jt)
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000).toFixed(1) + ' Jt';
                                if (value >= 1000) return (value / 1000).toFixed(0) + ' Rb';
                                return value;
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                }
            }
        });

        // --- FUNGSI FILTER CHART (Vanilla JS) ---
        // Dipanggil saat user klik tombol filter (Semua / Lunas / Belum Lunas)
        function filterChart(filter) {
            let dataYangDipilih;
            let labelDataset;
            let warnaGaris;
            let gradientWarna;

            // Pilih dataset yang sesuai berdasarkan filter yang diklik
            if (filter === 'lunas') {
                dataYangDipilih = dataLunasBulan;
                labelDataset = 'Penjualan Lunas';
                warnaGaris = '#10b981'; // Hijau emerald untuk lunas

                // Buat gradient baru untuk warna hijau
                const g = ctx.createLinearGradient(0, 0, 0, 280);
                g.addColorStop(0, 'rgba(16, 185, 129, 0.15)');
                g.addColorStop(1, 'rgba(16, 185, 129, 0)');
                gradientWarna = g;

            } else if (filter === 'belum_lunas') {
                dataYangDipilih = dataBelumBulan;
                labelDataset = 'Penjualan Belum Lunas';
                warnaGaris = '#f97316'; // Oranye untuk belum lunas

                const g = ctx.createLinearGradient(0, 0, 0, 280);
                g.addColorStop(0, 'rgba(249, 115, 22, 0.15)');
                g.addColorStop(1, 'rgba(249, 115, 22, 0)');
                gradientWarna = g;

            } else {
                // Default: tampilkan semua
                dataYangDipilih = dataSemuaBulan;
                labelDataset = 'Total Penjualan';
                warnaGaris = '#06728A';
                gradientWarna = gradientHijau; // Pakai gradient awal
            }

            // Update data dan tampilan chart tanpa reload halaman
            salesChart.data.datasets[0].data = dataYangDipilih;
            salesChart.data.datasets[0].label = labelDataset;
            salesChart.data.datasets[0].borderColor = warnaGaris;
            salesChart.data.datasets[0].backgroundColor = gradientWarna;
            salesChart.data.datasets[0].pointBackgroundColor = warnaGaris;
            salesChart.update('active'); // 'active' = animasi smooth saat update

            // --- Update Tampilan Tombol Filter ---
            // Hapus style "aktif" dari semua tombol, lalu berikan ke yang diklik
            document.querySelectorAll('.chart-filter-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'text-[#06728A]', 'text-emerald-600', 'text-orange-500',
                    'shadow-sm');
                btn.classList.add('text-gray-500');
            });

            const btnAktif = document.querySelector(`[data-filter="${filter}"]`);
            btnAktif.classList.add('bg-white', 'shadow-sm');
            btnAktif.classList.remove('text-gray-500');

            // Warna teks tombol aktif sesuai tema dataset
            if (filter === 'lunas') {
                btnAktif.classList.add('text-emerald-600');
            } else if (filter === 'belum_lunas') {
                btnAktif.classList.add('text-orange-500');
            } else {
                btnAktif.classList.add('text-[#06728A]');
            }
        }
    </script>

@endsection
