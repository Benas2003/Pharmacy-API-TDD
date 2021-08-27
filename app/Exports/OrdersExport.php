<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrdersExport implements FromCollection
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return Order::where('status', 'Ordered')->where('created_at','like', date('Y-m-d').'%')->get();
    }
}
