<?php

namespace App\Exports;

use App\Domain\Order\Repository\OrderRepository;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrdersExport implements FromCollection
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        $orderRepository = new OrderRepository();


        $title = [
            'id'=>'Order ID',
            'EUR_INT_O'=>'EUR-INT-O Serial No.',
            'name'=> 'Name',
            'amount'=>'Amount',
            'price'=>'Price',
            'created_at'=>'Order created at'
        ];

        $orders = $orderRepository->getAllOrdersFromSpecificRows();
        $orders->prepend($title);
        return $orders;
    }
}
