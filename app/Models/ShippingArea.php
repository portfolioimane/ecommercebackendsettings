<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingArea extends Model
{
    use HasFactory;

    // Make sure to include the correct fillable fields
    protected $fillable = [
        'name',
        'shipping_cost',
        'delivery_time',
        'active',
    ];

    // Define relationship to the Order model
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
