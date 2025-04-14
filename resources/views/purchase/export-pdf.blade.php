<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $purchase->invoice }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 30px;
            color: #000;
        }
        h2, h4 {
            margin-bottom: 5px;
        }
        .info {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            padding: 6px 8px;
            border-bottom: 1px solid #ccc;
        }
        th {
            background-color: #f3f3f3;
            text-align: left;
        }
        .summary {
            margin-top: 10px;
        }
        .summary td {
            padding: 4px 8px;
        }
        .total-row {
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .thanks {
            margin-top: 30px;
            text-align: center;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('img/logo.jpg') }}" width="50">
    <h2><strong>Neo Kasir</strong></h2>
    <p>Alamat : Jl. Cikereteg, Kp.Anyar, Caringin, Bogor</p>
    <p>Telepon : +6281234567890</p>

    <div class="info">
        <p>Member Status : {{ $isMember ? 'Member' : 'Bukan Member' }}</p>
        <p>No. HP : 0{{ $isMember ? ($purchase->member->phone ?? '-') : '-' }}</p>
        <p>Bergabung Sejak :
            {{ $isMember ? (\Carbon\Carbon::parse($purchase->member->created_at)->format('d F Y') ?? '-') : '-' }}
        </p>
        <p>Poin Member : {{ $isMember ? ($purchase->member->point ?? 0) : '-' }}</p>
    </div>


    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase->products as $product)
                <tr>
                    <td>{{ $product['name'] }}</td>
                    <td>{{ $product['quantity'] }}</td>
                    <td class="text-right">Rp. {{ number_format($product['price'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp. {{ number_format($product['total'], 0, ',', '.') }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>

    <table class="summary">
        <tr>
            <td>Poin Digunakan</td>
            <td class="text-right">{{ $purchase->discount_amount ?? 0 }}</td>
        </tr>
        <tr class="total-row">
            <td>Total Harga</td>
            <td class="text-right">Rp. {{ number_format($purchase->total_amount - $purchase->discount_amount, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>Harga Setelah Poin</td>
            <td class="text-right">Rp. {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>Jumlah Bayar</td>
            <td class="text-right">Rp. {{ number_format($purchase->payment_amount, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>Total Kembalian</td>
            <td class="text-right">Rp. {{ number_format($purchase->return_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        {{ \Carbon\Carbon::parse($purchase->created_at)->format('Y-m-d H:i:s') }} | Petugas: {{ $purchase->kasir->name ?? 'Petugas' }}
    </div>

    <div class="thanks">
        Terima kasih atas pembelian Anda!
    </div>
</body>
</html>
