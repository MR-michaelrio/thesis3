<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceItem extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'invoice_item';

    // Primary key
    protected $primaryKey = 'id_item';

    // Fillable fields
    protected $fillable = [
        'id_invoice',
        'item',
        'currency',
        'price',
        'discount',
        'sub_total',
        'id_company',
    ];

    public function invoiceitem()
    {
        return $this->belongsTo(InvoiceItem::class, 'id_invoice', 'id_invoice_hdrs');
    }


    
}
