<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Services\WhatsAppService;

$number = '628123456789'; // Dummy number or real one if user provided
$message = "Test Media from script";
$filename = "Test_Receipt.pdf";
$base64 = base64_encode(file_get_contents(__DIR__.'/../test_receipt.pdf'));

echo "Sending media to gateway...\n";
$sent = WhatsAppService::sendMedia($number, $message, $filename, $base64);

if ($sent) {
    echo "SUCCESS: Gateway accepted the media.\n";
} else {
    echo "FAILED: Check laravel logs for 'WA Gateway Media Error Response'.\n";
}
