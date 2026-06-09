<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $order->invoice_number }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 15px;
            width: 80mm;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .divider { border-bottom: 1px dashed #000; margin: 10px 0; }
        .header h1 { font-size: 16px; margin: 0 0 5px 0; }
        .header p { margin: 2px 0; font-size: 10px; }
        table { w-full; width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        td { padding: 2px 0; vertical-align: top; }
        .item-name { padding-bottom: 2px; }
        .footer { text-align: center; font-size: 10px; margin-top: 15px; }
        
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

   

    <!-- Header Toko -->
    <div class="header text-center">
        <h1>NATUNA GROSIR</h1>
        <p>Jl. Jati Utama</p>
        <p>Telp: 0812-3456-7890</p>
    </div>

    <div class="divider"></div>

    <!-- Info Transaksi -->
    <table style="font-size: 10px;">
        <tr>
            <td>No. Trans</td>
            <td>: {{ $order->invoice_number }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td>: {{ $order->user->name ?? 'UMUM' }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Daftar Barang -->
    <table>
        @foreach($order->items as $item)
        <tr>
            <td colspan="3" class="item-name font-bold">{{ $item->product->name ?? 'Produk' }}</td>
        </tr>
        <tr>
            <td style="width: 25%">{{ $item->quantity }}x</td>
            <td style="width: 40%">{{ number_format($item->price_at_time, 0, ',', '.') }}</td>
            <td style="width: 35%" class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <!-- Total & Pembayaran -->
    @php
        // Mengambil data uang dari tabel order_payments jika ada
        $amountPaid = $order->payment ? $order->payment->amount : 0;
        $kembalian = $amountPaid - $order->grand_total;
    @endphp

    <table>
        <tr>
            <td class="font-bold">TOTAL BELANJA</td>
            <td class="font-bold text-right">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>TUNAI</td>
            <td class="text-right">Rp {{ number_format($amountPaid, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>KEMBALI</td>
            <td class="text-right">Rp {{ number_format($kembalian > 0 ? $kembalian : 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <!-- Status Hutang Jika Ada -->
    @if($order->payment_status == 'belum_lunas')
    <div class="divider"></div>
    <div class="text-center font-bold" style="font-size: 10px;">
        *** STATUS: BELUM LUNAS (PIUTANG) *** <br>
        Sisa Hutang: Rp {{ number_format($order->grand_total - $amountPaid, 0, ',', '.') }}
    </div>
    @endif

    <div class="divider"></div>

    <!-- Footer -->
    <div class="footer">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
    </div>

    <!-- Script untuk Otomatis Membuka Jendela Print -->
    <script>
        window.onload = function() {
            window.print();
            
           
        }
    </script>
</body>
</html>