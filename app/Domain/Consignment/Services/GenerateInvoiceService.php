<?php

namespace App\Domain\Consignment\Services;

use App\Domain\Consignment\DTO\InvoiceGeneratorDTO\GenerateInvoiceInput;
use App\Domain\User\Repository\UserRepository;
use App\Models\Consignment;
use Illuminate\Support\Collection;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;

class GenerateInvoiceService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function invoiceGenerator(GenerateInvoiceInput $generateInvoiceInput): Invoice
    {
        $consignment = $generateInvoiceInput->getConsignment();
        return Invoice::make("Consignment - #$consignment->id")
            ->template('consignment_template')
            ->series('EUR-INT-C')
            ->sequence($consignment->id)
            ->serialNumberFormat('{SERIES}{SEQUENCE}')
            ->seller($this->configureSeller($generateInvoiceInput->getUserName()))
            ->buyer($this->configureCustomer($consignment))
            ->dateFormat('Y/m/d')
            ->currencySymbol('€')
            ->currencyCode('EUR')
            ->addItems($this->getConsignmentItems($generateInvoiceInput->getConsignmentProducts()))
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator("'")
            ->currencyDecimalPoint('.')
            ->filename('Consignment' . '-000' . $consignment->id)
            ->logo(public_path('vendor/invoices/logo.png'))
            ->save('public');
    }

    private function getConsignmentItems(Collection $consignmentProducts): array
    {
        foreach ($consignmentProducts as $product) {
            $consignmentItems[] = (new InvoiceItem())->title($product->VSSLPR . ' | ' . $product->name)->pricePerUnit($product->price / $product->amount)->quantity($product->amount);
        }
        return $consignmentItems;
    }

    private function configureCustomer(Consignment $consignment): Party
    {
        return new Party([
            'name' => $this->userRepository->getUserNameById($consignment->department_id),
            'date' => $consignment->created_at,
        ]);
    }

    private function configureSeller(string $userName): Party
    {
        return new Party([
            'pharmacist' => $userName,
        ]);
    }
}
