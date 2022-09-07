<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'item_id',
        'quantity',
    ];
    
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
