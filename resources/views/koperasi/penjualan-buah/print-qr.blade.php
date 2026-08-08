<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Tag QR - Batch #{{ $transaksi->id }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;800&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        h1, h2, .outfit {
            font-family: 'Outfit', sans-serif;
        }
        @media print {
            body {
                background-color: #ffffff;
            }
            .no-print {
                display: none;
            }
            .print-card {
                border: none !important;
                box-shadow: none !important;
                margin: 0 auto !important;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="bg-slate-100 flex flex-col items-center justify-center min-h-screen p-4">

    <!-- Printable Card Tag -->
    <div class="print-card bg-white border border-slate-200 w-[350px] p-6 rounded-3xl shadow-xl flex flex-col items-center space-y-5 text-center relative overflow-hidden">
        <!-- Brand Indicator -->
        <div class="flex items-center gap-2.5 justify-center">
            <div class="w-8 h-8 flex items-center justify-center">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <span class="font-bold text-base outfit tracking-tight text-slate-900">Smart <span class="text-emerald-600">Agroindustri</span></span>
        </div>
        
        <div class="w-full border-t border-dashed border-slate-200 my-1"></div>

        <!-- QR Code Image -->
        <div class="p-3 border-2 border-slate-100 rounded-2xl bg-slate-50">
            @php
                $trackingUrl = route('public.track', $transaksi->tracking_token);
            @endphp
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($trackingUrl) }}" alt="QR Code" class="w-[180px] h-[180px] object-contain">
        </div>

        <!-- Token SKU Details -->
        <div>
            <span class="text-[9px] font-bold font-mono tracking-widest text-slate-400 uppercase block">Token SKU Batch</span>
            <span class="text-xs font-bold font-mono text-slate-700 select-all" id="tracking_token_text">{{ $transaksi->tracking_token }}</span>
        </div>

        <div class="w-full border-t border-slate-100 my-1"></div>

        <!-- Potato details -->
        <div class="w-full text-left space-y-2 text-xs text-slate-700">
            <div class="flex justify-between">
                <span class="text-slate-400">Varietas Kentang</span>
                <span class="font-bold text-slate-800">{{ $transaksi->jenisKentang->nama_jenis ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Grade Kualitas</span>
                <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md text-[11px]">{{ $transaksi->grade ?? 'Grade A' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Berat Bersih</span>
                <span class="font-bold text-slate-800 font-mono">{{ number_format($transaksi->jumlah_kg, 0, ',', '.') }} Kg</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Asal Koperasi</span>
                <span class="font-bold text-slate-800">{{ $transaksi->koperasi->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Tujuan</span>
                <span class="font-bold text-indigo-600">{{ $transaksi->pembeli->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Tanggal Kirim</span>
                <span class="font-bold text-slate-800 font-mono">{{ date('d M Y', strtotime($transaksi->tanggal_transaksi)) }}</span>
            </div>
        </div>

        <div class="w-full border-t border-dashed border-slate-200 my-1"></div>

        <!-- Scan guide note -->
        <div class="text-[10px] text-slate-400 leading-normal font-semibold">
            Pindai QR Code untuk memeriksa asal-usul, grade kualitas kentang, dan rute pengiriman logistik secara transparan.
        </div>
    </div>

    <!-- Auto-Print Trigger Script -->
    <script>
        window.onload = function() {
            // Trigger browser print after a slight delay to let QR code load
            setTimeout(function() {
                window.print();
            }, 600);
        };
    </script>
</body>
</html>
