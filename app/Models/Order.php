<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Include shipping_area_id in the fillable array
    protected $fillable = [
        'user_id', 
        'name', 
        'email', 
        'phone', 
        'address', 
        'total_price', 
        'discount', 
        'payment_method',
        'status',
        'shipping_area_id' // Added shipping_area_id field
    ];

    // Define relationship with OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Define relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define relationship with ShippingArea
    public function shippingArea()
    {
        return $this->belongsTo(ShippingArea::class);
    }
}
