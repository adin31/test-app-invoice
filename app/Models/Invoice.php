<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'subject',
        'due_date',
        'invoice_from_id',
        'invoice_for_id',
        'amount_total',
        'amount_tax',
        'amount_paid',
        'amount_due',
    ];
    
    public function invoice_item()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    
    public function invoice_from()
    {
        return $this->belongsTo(Client::class, 'invoice_from_id', 'id');
    }
    
    public function invoice_for()
    {
        return $this->belongsTo(Client::class, 'invoice_for_id', 'id');
    }
}
