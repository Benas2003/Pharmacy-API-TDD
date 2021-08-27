<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $primaryKey = 'id';

    protected $fillable = ['product_id', 'EUR_INT_O', 'name', 'amount', 'price', 'status'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
