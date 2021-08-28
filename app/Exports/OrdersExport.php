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
        $title = [
            'id'=>'Order ID',
            'EUR_INT_O'=>'EUR-INT-O Serial No.',
            'name'=> 'Name',
            'amount'=>'Amount',
            'price'=>'Price',
            'created_at'=>'Order created at'
        ];

        $orders = Order::select('id', 'EUR_INT_O', 'name', 'amount', 'price', 'created_at')->where('status', 'Ordered')->where('created_at','like', date('Y-m-d').'%')->get();
        $orders->prepend($title);
        return $orders;
    }
}
