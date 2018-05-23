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
     * Get the class name for polymorphic relations.
     *
     * @return string
     */
    public function type()
    {
        $morphMap = Relation::morphMap();

        if (! empty($morphMap) && in_array(static::class, $morphMap)) {
            return array_search(static::class, $morphMap, true);
        }

        return static::class;
    }
}
