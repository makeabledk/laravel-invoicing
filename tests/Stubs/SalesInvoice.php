<?php

namespace Makeable\LaravelInvoicing\Tests\Stubs;

use Illuminate\Support\Collection;
use Makeable\LaravelCurrencies\Amount;
use Makeable\LaravelInvoicing\Invoice;

class SalesInvoice extends \Makeable\LaravelInvoicing\InvoiceBuilder
{
    /**
     * @var Collection
     */
    protected $products;

    /**
     * SalesInvoice constructor.
     *
     * @param Collection $products
     */
    public function __construct(Collection $products)
    {
        $this->products = $products;
    }

    /**
     * @param Invoice $invoice
     * @return Invoice
     */
    public function invoice($invoice)
    {
        return $invoice
            ->setTotalAmount($total = Amount::sum($this->products, 'price_incl_vat'))
            ->setVatAmount($total->multiply(0.20));
    }

    /**
     * @param Invoice $invoice
     * @return Invoice
     */
    public function contents($invoice)
    {
        return 'foobar';
    }

    /**
     * @return Collection
     */
    public function tags()
    {
        return $this->products;
    }
}
