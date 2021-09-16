<?php

namespace App\Console\Commands;

use App\Domain\Order\Repository\OrderRepository;
use App\Exports\OrdersExport;
use App\Models\Order;
use App\Models\Product;
use Faker\Generator;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
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

    private OrderRepository $orderRepository;
    private Generator $generator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $orderRepository, Generator $generator)
    {
        $this->orderRepository = $orderRepository;
        $this->generator = $generator;
        parent::__construct();
    }


    public function handle(): void
    {
        $products = Product::all();

        $this->createOrdersForProducts($products);
        $this->exportDocument();

    }

    private function createOrdersForProducts(Collection|array $products): void
    {
        foreach ($products as $product) {

            if ($product->storage_amount > $product->amount) {
                $current_product_orders = $this->orderRepository->getAllOrdersWithOrderedStatus($product->id);

                $already_ordered_amount = $this->getAmountWhichIsAlreadyOrdered($current_product_orders);

                $this->createNewOrder($product, $already_ordered_amount);
            }
        }
    }

    private function getAmountWhichIsAlreadyOrdered($current_product_orders): int
    {
        $already_ordered_amount = 0;
        foreach ($current_product_orders as $order) {
            $already_ordered_amount += $order->amount;
        }
        return $already_ordered_amount;
    }

    private function createNewOrder(mixed $product, $already_ordered_amount): void
    {
        $ordering_amount = $product->storage_amount - ($product->amount + $already_ordered_amount);
        if ($ordering_amount > 0) {
            Order::create([
                'product_id' => $product->id,
                'EUR_INT_O' => $this->generator->uuid,
                'name' => $product->name,
                'amount' => $ordering_amount,
                'price' => $product->price * $ordering_amount,
            ]);
        }
    }

    private function exportDocument()
    {
        return Excel::store(new OrdersExport, 'orders-' . date('Y-m-d') . '.xlsx');
    }
}
