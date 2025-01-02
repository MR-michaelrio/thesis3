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

    // Auto incrementing primary key
    public $incrementing = true;

    // Key type
    protected $keyType = 'int';

    // Timestamps
    public $timestamps = true;

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

    // Relationships
    public function invoiceitem()
    {
        return $this->hasMany(InvoiceItem::class, 'id_invoice', 'id_invoice_hdrs');
    }


    
}
