<?php

namespace App\Console\Commands;

use App\Domain\Order\Repository\OrderRepository;
use App\Exports\OrdersExport;
use App\Models\Order;
use App\Models\Product;
use Faker\Factory;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class OrderProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Order products automatically based on their live count.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle() :void
    {

        $products = Product::all();
        $orderRepository = new OrderRepository();

        foreach ($products as $product)
        {
            $already_ordered_amount=0;
            if($product->storage_amount>$product->amount)
            {
                $current_product_orders = $orderRepository->getAllOrdersWithOrderedStatus($product->id);


                $already_ordered_amount = $this->checkAmountWhichIsAlreadyOrdered($current_product_orders, $already_ordered_amount);

                $this->createNewOrderBasedOnAlreadyOrderedAmount($product, $already_ordered_amount);
            }
        }
        $this->export();

    }

    private function export(): bool
    {
        return Excel::store(new OrdersExport, 'orders-'.date('Y-m-d').'.xlsx');
    }

    private function checkAmountWhichIsAlreadyOrdered($current_product_orders, $already_ordered_amount)
    {
        foreach ($current_product_orders as $order) {
            $already_ordered_amount += $order->amount;
        }
        return $already_ordered_amount;
    }

    private function createNewOrderBasedOnAlreadyOrderedAmount(mixed $product, $already_ordered_amount): void
    {
        $faker = Factory::create();

        $ordering_amount = $product->storage_amount - ($product->amount + $already_ordered_amount);
        if ($ordering_amount > 0) {
            Order::create([
                'product_id' => $product->id,
                'EUR_INT_O' => $faker->uuid,
                'name' => $product->name,
                'amount' => $ordering_amount,
                'price' => $product->price * $ordering_amount,
            ]);
        }
    }
}
