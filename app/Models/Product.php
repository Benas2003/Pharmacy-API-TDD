<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
