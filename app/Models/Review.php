<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'customer_id',  // vztah k zákazníkovi
        'product_id',   // vztah k produktu
        'rating',       // hodnocení (např. 1-5 hvězdiček)
        'comment',      // komentář k recenzi
    ];

    // Vztah: recenze patří k jednomu zákazníkovi
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Vztah: recenze patří k jednomu produktu
    public function product()
    {
        return $this->belongsTo(Product::class);  // Model Product musíte mít také
    }
}
