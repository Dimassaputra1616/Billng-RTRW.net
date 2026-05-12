<!DOCTYPE html>
<html>
<head>
    <title>Kwitansi Pembayaran</title>
    <style>
        /* General Setup */
        @page {
            margin: 0.5cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }

        /* Watermark LUNAS */
        .watermark {
            position: fixed;
            top: 40%;
            left: 25%;
            transform: rotate(-35deg);
            opacity: 0.15;
            color: #10b981; /* Green color */
            font-size: 100px;
            font-weight: bold;
            z-index: -1;
            text-align: center;
            width: 100%;
        }

        /* Header Layout */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1e1b4b;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .company-info h1 {
            font-size: 20px;
            color: #1e1b4b;
            margin: 0;
            text-transform: uppercase;
        }
        .company-info p {
            margin: 2px 0;
            color: #666;
            font-size: 10px;
        }
        .receipt-title {
            text-align: right;
            vertical-align: top;
        }
        .receipt-title h2 {
            font-size: 24px;
            color: #1e1b4b;
            margin: 0;
            letter-spacing: 2px;
        }
        .receipt-title p {
            margin: 2px 0;
            font-weight: bold;
        }

        /* Customer Box */
        .info-section {
            margin-bottom: 30px;
        }
        .customer-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            width: 60%;
            border-radius: 4px;
        }
        .customer-box h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .customer-box p {
            margin: 3px 0;
            font-size: 13px;
            font-weight: bold;
        }
        .payment-method {
            margin-top: 5px;
            font-size: 11px;
            color: #1e1b4b;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #1e1b4b;
            color: white;
            text-align: left;
            padding: 10px;
            font-size: 11px;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .description-col {
            width: 70%;
        }
        .price-col {
            width: 30%;
            text-align: right;
            font-weight: bold;
        }

        /* Total Section */
        .total-container {
            float: right;
            width: 40%;
        }
        .total-table {
            width: 100%;
            border-collapse: collapse;
        }
        .total-table td {
            padding: 5px 10px;
        }
        .total-label {
            text-align: right;
            color: #64748b;
            font-weight: bold;
        }
        .total-value {
            text-align: right;
            font-size: 16px;
            color: #1e1b4b;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            clear: both;
            margin-top: 100px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="watermark">LUNAS</div>

    <table class="header-table">
        <tr>
            <td class="company-info">
                <!-- <img src="path_to_logo.png" style="height: 50px; margin-bottom: 10px;"> -->
                <h1>RT RW NET PRO</h1>
                <p>Jl. Pahlawan No. 123, Kota Digital</p>
                <p>WhatsApp: 0812-3456-7890 | Email: support@rtrwnet.pro</p>
            </td>
            <td class="receipt-title">
                <h2>KWITANSI</h2>
                <p>#{{ $payment->invoice->invoice_number }}</p>
                <p style="font-weight: normal; font-size: 11px; color: #64748b;">Tanggal Bayar: {{ $payment->payment_date->format('d/m/Y') }}</p>
            </td>
        </tr>
    </table>

    <div class="info-section">
        <div class="customer-box">
            <h3>Tagihan Kepada:</h3>
            <p>{{ $payment->invoice->customer->name }}</p>
            <div class="payment-method">
                Metode Pembayaran: <strong>{{ strtoupper($payment->payment_method) }}</strong>
            </div>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th class="description-col">Deskripsi</th>
                <th class="price-col">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="description-col">
                    @php
                        $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    @endphp
                    Pembayaran {{ $payment->invoice->customer->internetPackage->name }} <br>
                    <small style="color: #64748b;">Periode {{ $bulan[$payment->invoice->period_month] }} {{ $payment->invoice->period_year }}</small>
                </td>
                <td class="price-col">
                    Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="total-container">
        <table class="total-table">
            <tr>
                <td class="total-label">TOTAL BAYAR</td>
                <td class="total-value">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Terima kasih telah berlangganan layanan <strong>RT RW NET PRO</strong>.</p>
        <p>Bukti pembayaran ini sah dan diterbitkan secara otomatis oleh sistem.</p>
        <p style="margin-top: 5px;">Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
