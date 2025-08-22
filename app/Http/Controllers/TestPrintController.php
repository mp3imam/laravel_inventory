<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use charlieuki\ReceiptPrinter\ReceiptPrinter as ReceiptPrinter;

class TestPrintController extends Controller
{
    //
    public function index() {
    $mid = '123123456';
    $store_name = 'KEJAKSAAN AGUNG REPUBLIK INDONESIA';
    $store_address = 'KEJARI KABUPATEN BEKASI';
    $store_phone = '1234567890';
    $store_email = 'yourmart@email.com';
    $store_website = 'yourmart.com';
    $tax_percentage = 10;
    $transaction_id = 'TX123ABC456';
    $currency = 'Rp';
    $image_path = public_path() . '/assets/images/logo/kejaksaan-logo.png';

    // Init printer
    $printer = new ReceiptPrinter;
    $printer->init(
        config('receiptprinter.connector_type'),
        config('receiptprinter.connector_descriptor')
    );

    $printer->setLogo($image_path);

    // Set store info
    $printer->setStore($mid, $store_name, $store_address, $store_phone, $store_email, $store_website);

    // Set currency

    // Set logo
    // Uncomment the line below if $image_path is defined
    //$printer->setLogo($image_path);

    // Set QR code
    $printer->setTextSize('0001');

    // Print payment request
    $printer->printRequest();

     return 'berhasil';
    }

}
