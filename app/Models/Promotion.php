<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image', 'is_active', 'expires_at'];

    // Check if the promotion is active
    public function isActive()
    {
        return $this->is_active && ($this->expires_at === null || Carbon::now()->isBefore($this->expires_at));
    }
}

