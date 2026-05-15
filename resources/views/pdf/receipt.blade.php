<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kwitansi Pembayaran #{{ $payment->id }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #5d5fef; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #5d5fef; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 14px; }
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-table td { padding: 5px 0; vertical-align: top; }
        .label { color: #888; font-size: 12px; text-transform: uppercase; font-weight: bold; }
        .value { color: #333; font-size: 15px; font-weight: bold; }
        .receipt-body { background: #f9f9f9; padding: 30px; border-radius: 10px; border: 1px solid #eee; }
        .total-box { margin-top: 30px; text-align: right; border-top: 2px dashed #ddd; padding-top: 20px; }
        .total-box h2 { margin: 0; color: #5d5fef; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #aaa; }
        .watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 80px; color: rgba(0,255,0,0.05); font-weight: bold; z-index: -1; }
    </style>
</head>
<body>
    <div class="watermark">LUNAS</div>

    <div class="header">
        <h1>VeloNet</h1>
        <p>Solusi Internet Cepat & Stabil</p>
        <p>RT RW NET Management System</p>
    </div>

    <div class="receipt-body">
        <table class="info-table">
            <tr>
                <td width="50%">
                    <div class="label">DITERIMA DARI</div>
                    <div class="value">{{ $payment->customer->name }}</div>
                </td>
                <td width="50%" style="text-align: right;">
                    <div class="label">NOMOR KWITANSI</div>
                    <div class="value">#REC-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <div class="label">UNTUK PEMBAYARAN</div>
                    <div class="value">Tagihan #{{ $payment->invoice->invoice_number }}</div>
                    <div class="value">Periode {{ $payment->invoice->period_month }}/{{ $payment->invoice->period_year }}</div>
                </td>
                <td width="50%" style="text-align: right;">
                    <div class="label">TANGGAL BAYAR</div>
                    <div class="value">{{ $payment->payment_date->format('d F Y H:i') }}</div>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <div class="label">METODE PEMBAYARAN</div>
                    <div class="value">{{ strtoupper($payment->payment_method) }}</div>
                </td>
                <td width="50%" style="text-align: right;">
                    <div class="label">STATUS</div>
                    <div class="value" style="color: green;">LUNAS</div>
                </td>
            </tr>
        </table>

        <div class="total-box">
            <div class="label">TOTAL DIBAYARKAN</div>
            <h2>Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</h2>
        </div>
    </div>

    <div class="footer">
        <p>Kwitansi ini diterbitkan secara otomatis oleh sistem VeloNet dan merupakan bukti pembayaran yang sah.</p>
        <p>&copy; {{ date('Y') }} VeloNet RT RW Net</p>
    </div>
</body>
</html>
