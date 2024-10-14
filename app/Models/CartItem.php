<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_variant_id', // Reference to the product variant
        'product_id',
        'quantity',
        'price',
        'image',
    ];

    /**
     * Get the product that this cart item refers to.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product variant associated with the cart item.
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get the cart that owns this item.
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Calculate the total price for this cart item (taking into account product variant price adjustment).
     */
    public function getTotalPrice()
    {
        // Fetch the base product price
        $basePrice = $this->product ? $this->product->price : 0;

        // Adjust the price if the item has a variant
        if ($this->productVariant) {
            return $this->productVariant->getTotalPrice($basePrice) * $this->quantity;
        }

        // If no variant, just multiply base price by quantity
        return $basePrice * $this->quantity;
    }
}
