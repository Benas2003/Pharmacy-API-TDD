<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consignment extends Model
{
    use HasFactory;

    protected $table = 'consignments';

    protected $primaryKey = 'id';

    protected $fillable = ['department_name', 'status'];

    public function consignmentProducts()
    {
        return $this->hasMany(ConsignmentProduct::class);
    }

}
