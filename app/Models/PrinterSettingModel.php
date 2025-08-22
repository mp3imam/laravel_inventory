<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrinterSettingModel extends Model
{
    use HasFactory;
    protected $table = 'printer_kiosk';
    protected $guarded = ['id'];
}