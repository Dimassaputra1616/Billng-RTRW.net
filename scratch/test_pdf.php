<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payment;

try {
    $payment = Payment::latest()->first();
    if (!$payment) {
        die("No payment found\n");
    }
    echo "Generating PDF for Payment #{$payment->id}...\n";
    $pdf = Pdf::loadView('pdf.receipt', ['payment' => $payment]);
    $output = $pdf->output();
    file_put_contents('test_receipt.pdf', $output);
    echo "PDF generated successfully: test_receipt.pdf (" . strlen($output) . " bytes)\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
