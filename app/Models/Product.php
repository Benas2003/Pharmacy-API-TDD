<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $fillable = [
        'VSSLPR',
        'name',
        'amount',
        'storage_amount',
        'price',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function consignmentProduct(): HasMany
    {
        return $this->hasMany(ConsignmentProduct::class);
    }
}
