<?php

namespace App\Domain\Consignment\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;

class generateInvoiceService
{
    public function generateInvoice($consignment, $consignment_products): Invoice
    {
        return Invoice::make("Consignment - #$consignment->id")
            ->template('consignment_template')
            ->series('EUR-INT-C')
            ->sequence($consignment->id)
            ->serialNumberFormat('{SERIES}{SEQUENCE}')
            ->seller($this->configureSeller())
            ->buyer($this->configureCustomer($consignment))
            ->dateFormat('Y/m/d')
            ->currencySymbol('€')
            ->currencyCode('EUR')
            ->addItems($this->getConsignmentItems($consignment_products))
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator("'")
            ->currencyDecimalPoint('.')
            ->filename('Consignment' . '-000' . $consignment->id)
            ->logo(public_path('vendor/invoices/logo.png'))
            ->save('public');
    }

    private function getConsignmentItems($consignment_products): array
    {
        foreach ($consignment_products as $product) {
            $consignment_items[] = (new InvoiceItem())->title($product->VSSLPR . ' | ' . $product->name)->pricePerUnit($product->price / $product->amount)->quantity($product->amount);
        }
        return $consignment_items;
    }

    private function configureCustomer($consignment): Party
    {
        return new Party([
            'name' => User::select('name')->where('id', $consignment->department_id)->get(),
            'date' => $consignment->created_at,
        ]);
    }

    private function configureSeller(): Party
    {
        return new Party([
            'pharmacist' => Auth::user()->name,
        ]);
    }
}
