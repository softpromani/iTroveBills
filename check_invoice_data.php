<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;

$res = [
    'invoice_items_columns' => Schema::getColumnListing('invoice_items'),
    'invoices_columns' => Schema::getColumnListing('invoices'),
    'invoice_7' => Invoice::with('invoiceitems')->find(7)?->toArray()
];

file_put_contents('invoice_data_dump.json', json_encode($res, JSON_PRETTY_PRINT));
echo "Done\n";

