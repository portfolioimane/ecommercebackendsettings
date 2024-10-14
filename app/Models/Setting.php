<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's convention
    protected $table = 'settings';

    // Allow mass assignment for the following fields
    protected $fillable = [
        'type', // The type of setting (e.g., 'stripe', 'paypal', etc.)
        'value', // The value of the setting, can be JSON or string
    ];

    // Cast the value attribute to an array if stored as JSON
    protected $casts = [
        'value' => 'array', // Automatically converts JSON string to array and vice versa
    ];
}
