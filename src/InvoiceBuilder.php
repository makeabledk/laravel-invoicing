<?php

namespace Makeable\LaravelInvoicing;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

abstract class InvoiceBuilder
{
    /**
     * @param Invoice $invoice
     *
     * @return Invoice
     */
    abstract public function invoice($invoice);

    /**
     * @param Invoice $invoice
     *
     * @return Invoice
     */
    abstract public function contents($invoice);

    /**
     * @param Invoice $invoice
     */
    public function filename($invoice)
    {
    }

    /**
     * @return Collection | null
     */
    public function tags()
    {
    }

    /**
     * @return string
     */
    public function type()
    {
        return static::class;
    }
}
