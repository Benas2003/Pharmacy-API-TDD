<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsignmentProduct extends Model
{
    use HasFactory;

    protected $table = 'consignment_products';

    protected $primaryKey = 'id';

    protected $fillable = ['consignment_id', 'product_id', 'VSSLPR', 'name', 'amount', 'price'];

    public function consignment(): BelongsTo
    {
        return $this->belongsTo(Consignment::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
