<?php

namespace Makeable\LaravelInvoicing\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Makeable\LaravelInvoicing\Invoice;

class InvoiceCreated
{
    use SerializesModels, Dispatchable;

    /**
     * @var Invoice
     */
    public $invoice;

    /**
     * @param Invoice $invoice
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }
}
