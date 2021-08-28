<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsignmentProduct extends Model
{
    use HasFactory;

    protected $table = 'consignment_products';

    protected $primaryKey = 'id';

    protected $fillable = ['consignment_id', 'VSSLPR', 'name', 'amount', 'price'];

    public function consignment()
    {
        return $this->belongsTo(Consignment::class);
    }
}
