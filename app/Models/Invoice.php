<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'invoice_hdrs';

    // Primary key
    protected $primaryKey = 'id_invoice_hdrs';

    // Auto incrementing primary key
    public $incrementing = true;

    // Key type
    protected $keyType = 'int';

    // Timestamps
    public $timestamps = true;

    // Fillable fields
    protected $fillable = [
        'invoice_number',
        'payment_due',
        'id_company',
        'total',
        'payment_status',
        'payed_amount',
        'evidence',
        'user_comment',
        'period_end',
        'period_start',
        'tax',
        'created_at'
    ];

    // Relationships
    public function invoiceitem()
    {
        return $this->hasMany(InvoiceItem::class, 'id_invoice', 'id_invoice_hdrs');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company', 'id_company');
    }
}